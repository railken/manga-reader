<?php

namespace App\Scrapers;

use Closure;
use Exception;

class BaseScraper {

    public function retryFor(int $retry, Closure $callback)
    {
    	if ($retry <= 0) {
    		throw new \Exception("Failed");
    	}

    	try {
        	return $callback();
        } catch (Exception $e) {
        	return $this->retryFor($retry - 1, $callback);
        }
    }
}