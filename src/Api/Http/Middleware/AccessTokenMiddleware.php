<?php

namespace Api\Http\Middleware;

use Symfony\Component\HttpFoundation\HeaderBag;
use Closure;

class AccessTokenMiddleware
{
    public function handle($request, Closure $next)
    {
    	$request->server->set('HTTP_ACCEPT', 'application/json'); 

    	if ($request->input('access_token'))
    		$request->server->set('HTTP_AUTHORIZATION', 'Bearer '.$request->input('access_token'));


    	$request->headers = new HeaderBag($request->server->getHeaders()); 

    	return $next($request); 

    }
}