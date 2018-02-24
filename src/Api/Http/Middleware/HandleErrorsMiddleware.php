<?php

namespace Api\Http\Middleware;

use Closure;
use DB;
use Exception;
use Api\Exceptions\BadRequestException;
use Illuminate\Http\JsonResponse;

class HandleErrorsMiddleware
{
    public function handle($request, Closure $next)
    {
        DB::beginTransaction();

        $response = $next($request);
        $exception = $response->exception;

        if ($exception) {
            DB::rollback();

            if ($exception instanceof BadRequestException) {
                $message = $exception->getMessage();
                $response = new JsonResponse(['status' => 'error', 'message' => 'Bad Request', 'errors' => $message], 400);
            }
        }

        if (!$exception) {
            DB::commit();
        }
        
        return $response;
    }

    public function terminate($request, $response)
    {
    }
}
