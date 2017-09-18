<?php

namespace Api\Http\Middleware;

use Closure;
use DB;
use Exception;

class HandleErrorsMiddleware
{
    public function handle($request, Closure $next)
    {

		DB::beginTransaction();

        $response = $next($request);
        $exception = $response->exception;

        if ($exception) {

            DB::rollback();
            // $response = // A new response is generated here.
        }

        if (!$exception)
            DB::commit();
        
        return $response;
    }

    public function terminate($request, $response)
    {
        
    }
}