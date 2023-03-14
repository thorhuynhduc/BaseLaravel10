<?php

namespace Core\Http\Middleware;

use Closure;
use Core\Enums\Language;
use Illuminate\Http\Request;

class LanguageCode
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $lang = $request->headers->get('accept-language');

        if ($lang && in_array($lang, array_column(Language::cases(), 'value'))) {
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
