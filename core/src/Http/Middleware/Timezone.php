<?php

namespace Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Timezone
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $tz = $request->headers->get('accept-timezone');

        $default = config('app.client_timezone');
        config(['app.client_timezone' => $tz ?: $default ?: 'UTC']);

        return $next($request);
    }
}
