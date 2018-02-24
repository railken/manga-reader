<?php

namespace Sync\Console\Commands;

use Illuminate\Console\Command;
use Core\Manga\MangaManager;

class SyncReleasesCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:releases {pages=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sync the chapters of a manga';

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
        dispatch((new \Sync\Jobs\SyncReleasesJob($this->argument('pages')))->onQueue('sync.index'));
    }
}
