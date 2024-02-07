<?php

namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        @$url = explode('/', $request->url());
        if((@$url['3']=='download-csv-file' || @$url['3']=='download-tw-click-report' || @$url['3']=='download-tw-response-report' || @$url['3']=='export-number-date-wise-report') && \Auth::check())
        {
            return $next($request);
        }
        $response = $next($request);
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
