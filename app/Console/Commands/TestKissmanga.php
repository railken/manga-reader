<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Railken\Kissmanga\Kissmanga;

class TestKissmanga extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:kissmanga';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api = new Kissmanga();
        
        $result = $api->releases()->page(1)->get();

        $result->results->count() === 0 ? $this->error("Something went wrong") : $this->info("All ok!");
    }
}
