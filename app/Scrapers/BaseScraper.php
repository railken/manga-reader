<?php

namespace App\Scrapers;

use Closure;
use Exception;

class BaseScraper {

    public function retryFor(int $retry, Closure $callback, int $sleep = 5)
    {
    	if ($retry <= 0) {
    		throw new \Exception("Failed");
    	}

    	try {
        	return $callback();
        } catch (Exception $e) {
        	sleep($sleep);
        	return $this->retryFor($retry - 1, $callback);
        }
    }
}