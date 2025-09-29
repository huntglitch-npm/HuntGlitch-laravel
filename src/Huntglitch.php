<?php

namespace Itpath\Huntglitch;

use Throwable;


class Huntglitch
{
    public $projectId = '';
    public $deliverableId = '';
    public $logEndpoint = '';
    public $systemTags = [];
    public $payload = [];

    public function __construct()
    {
        $this->projectId = config('huntglitch.project_id');
        $this->deliverableId = config('huntglitch.deliverable_id');
        $this->logEndpoint = config('huntglitch.log_endpoint');

        $st = new SystemTags;
        $this->systemTags = $st->generateTags();
    }

    public function addError($data, array $additionalData = [])
    {
        return $this->add($data, 'error', $additionalData);
    }

    public function addWarning($data, array $additionalData = [])
    {
        return $this->add($data, 'warning', $additionalData);
    }

    public function addInfo($data, array $additionalData = [])
    {
        return $this->add($data, 'info', $additionalData);
    }

    public function addDebug($data, array $additionalData = [])
    {
        return $this->add($data, 'debug', $additionalData);
    }

    public function addNotice($data, array $additionalData = [])
    {
        return $this->add($data, 'notice', $additionalData);
    }

    public function add($data, $errorType = '', array $additionalData = [])
    {
        if ($this->projectId == '') {
            return 'Project Key Not Found';
        }

        if ($this->deliverableId == '') {
            return 'Deliverable Key Not Found';
        }

        $payload = [];
        $payloadsData = [];

        if ($data instanceof \Throwable) {
            $payloadsData['b'] = $this->getErrorData($data);
        } else {
            $payloadsData['b']['c'] = (string) $data;
        }

        $st = new Severity;
        if ($errorType == '' && $data instanceof \ErrorException) {
            $type = $st->getErrorTypeFromException($data->getSeverity());
        } else {
            $type = $st->getErrorTypeFromString($errorType);
        }

        $payloadsData['i'] = $additionalData;
        $payloadsData['j'] = $this->systemTags;
        $payloadsData['k'] = request()->header();
        $payloadsData['l'] = request()->all();
        $payloadsData['m'] = request()->url();
        $payloadsData['n'] = request()->method();

        $payload = [
            'vp' => $this->projectId,
            'vd' => $this->deliverableId,
            'o' => $type,
            'a' => json_encode($payloadsData), // a means data
            'r' => request()->ip(),
        ];

        $result = $this->sendPayload($payload);

        return $result;

    }

    public function sendPayload(array $postPayload)
    {
        try {

            $baseUrl = ($this->logEndpoint == '') ? 'https://api.huntglitch.com/' : $this->logEndpoint;

            $ch = curl_init($baseUrl.'add-log');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postPayload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        } catch (Throwable $e) {

            return 'Log Connection Error';

        } catch (\Exception $e) {

            return 'Log Connection Error';
        }
    }

    protected function getErrorData(Throwable $e)
    {

        $errorData = [];

        if ($e != null) {

            $traceData = [];

            foreach ($e->getTrace() as $trace) {
                if (isset($trace['file']) && isset($trace['line'])) {
                    $data = $this->getFileContent(5, $trace['file'], $trace['line']);
                    $data['lineno'] = $trace['line'];
                    $data['file'] = $trace['file'];
                    $traceData[] = $data;
                }
            }

            $errorData = [
                'c' => $e->getMessage(),
                'd' => $e->getFile(),
                'e' => $traceData,
                'f' => $e->getLine(),
                'g' => $e->getCode(),
                'h' => get_class($e),
            ];
        }

        return $errorData;
    }

    protected function getFileContent(int $maxContextLines, string $filePath, int $lineNumber)
    {

        $frame = [
            'pre_context' => [],
            'context_line' => null,
            'post_context' => [],
        ];

        $target = max(0, ($lineNumber - ($maxContextLines + 1)));
        $currentLineNumber = $target + 1;

        try {
            $file = new \SplFileObject($filePath);
            $file->seek($target);

            while (! $file->eof()) {

                $line = $file->current();
                $line = rtrim($line, "\r\n");

                if ($currentLineNumber === $lineNumber) {
                    $frame['context_line'] = $line;
                } elseif ($currentLineNumber < $lineNumber) {
                    $frame['pre_context'][] = $line;
                } elseif ($currentLineNumber > $lineNumber) {
                    $frame['post_context'][] = $line;
                }

                $currentLineNumber++;

                if ($currentLineNumber > $lineNumber + $maxContextLines) {
                    break;
                }

                $file->next();
            }
        } catch (\Exception $e) {

            return $frame;
        }

        return $frame;
    }
}
