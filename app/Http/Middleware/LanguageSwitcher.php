<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = $request->get('lang');
        switch ($language) {
            case 'deu':
                $language = 'de';
                break;
            default:
                $language = 'en';
                break;
        }
        App::setLocale($language);
        return $next($request);
    }
}
