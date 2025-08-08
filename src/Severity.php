<?php

namespace Itpath\Huntglitch;

class Severity
{
    public $error_types = [
        1 => 'debug',
        2 => 'warning',
        3 => 'notice',
        4 => 'info',
        5 => 'error',
    ];

    const severityType = [
        'debug',
        'warning',
        'notice',
        'info',
        'error',
    ];

    protected function fromError(int $severity)
    {
        switch ($severity) {
            case \E_DEPRECATED:
            case \E_USER_DEPRECATED:
            case \E_WARNING:
            case \E_USER_WARNING:
            case \E_CORE_WARNING:
            case \E_COMPILE_WARNING:
                return 'warning';
            case \E_ERROR:
            case \E_CORE_ERROR:
            case \E_COMPILE_ERROR:
            case \E_USER_ERROR:
            case \E_RECOVERABLE_ERROR:
            case \E_PARSE:
                return 'error';
            case \E_NOTICE:
            case \E_USER_NOTICE:
            case \E_STRICT:
                return 'notice';
            default:
                return 'error';
        }
    }

    public function getErrorTypeFromString($type)
    {
        $logType = array_search(strtolower($type), $this->error_types, true);
        if (! $logType) {
            $logType = 5;
        }

        return $logType;
    }

    public function getErrorTypeFromException($severity)
    {

        $errorType = $this->fromError($severity);

        return $this->getErrorTypeFromString($errorType);
    }
}
