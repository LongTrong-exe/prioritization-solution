<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSignature
{

    public const GET_PARAMETER_SIGNATURE = 'signature';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->verifySignature($request->fullUrl(), $request->get(self::GET_PARAMETER_SIGNATURE))) {
            return view('wrong-signature');
        }

        return $next($request);
    }

    /**
     * Check the signature from onOffice request url
     *
     * @param string $inUrl
     * @param string $signature
     * @return bool
     */
    public function verifySignature(string $inUrl, string $signature = null): bool
    {
        $queryParameters = [];

        $urlElements = parse_url($inUrl);

        if (empty($urlElements['query'])) {
            return false;
        }

        $queryValue = $urlElements['query'];

        parse_str($queryValue, $queryParameters);

        unset($queryParameters['signature']);
        ksort($queryParameters);

        $cleanSourceUrl = $urlElements['scheme'] . '://' . $urlElements['host'] . $urlElements['path'];
        $uriToCheck = $cleanSourceUrl . '?' . http_build_query($queryParameters);

        $checkSignature = hash_hmac('sha256', $uriToCheck, env('ONOFFICE_PROVIDER_SECRET'));

        return $checkSignature === $signature;
    }
}
