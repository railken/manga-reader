<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sync the manga index';

    /**
     * The drip e-mail service.
     *
     * @var DripEmailer
     */
    protected $drip;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch((new \App\Jobs\IndexerJob())->onQueue('sync.index'));
    }
}
