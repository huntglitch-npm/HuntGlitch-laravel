<?php

namespace Itpath\Huntglitch;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class JsLogController extends BaseController
{
    public function receive(Request $request)
    {
        // Domain verification
        $allowedDomainsRaw = config('huntglitch.js_domain', '');
        // You can now specify multiple allowed domains in your .env or config like this:
        // HUNTGLITCH_JS_DOMAIN=example.com,anotherdomain.com,sub.site.com

        $allowedDomains = [];
        if (!empty($allowedDomainsRaw)) {
            $allowedDomains = array_map('trim', explode(',', $allowedDomainsRaw));
        }
        $origin = $request->header('Origin') ?? $request->header('Referer');
        $isAllowed = false;
        if ($origin && count($allowedDomains)) {
            foreach ($allowedDomains as $domain) {
                if ($domain && stripos($origin, $domain) !== false) {
                    $isAllowed = true;
                    break;
                }
            }
        } else {
            $isAllowed = true; // If no domains configured, allow all (for dev)
        }
        if (! $isAllowed) {
            return response()->json(['error' => 'Origin not allowed'], 403);
        }

        // Build payload for Huntglitch
        $huntglitch = new Huntglitch();
        $project_id = $huntglitch->project_id;
        $deliverable_id = $huntglitch->deliverable_id;

        $raw = $request->all();
        $payload = [
            'vp' => $project_id,
            'vd' => $deliverable_id,
            'o' => $request->input('o', 0),
            'a' => $request->input('a', ''),
            'r' => $request->ip(),
        ];

        $result = $huntglitch->sendPayload($payload);

        return response()->json(['result' => $result]);
    }
}
