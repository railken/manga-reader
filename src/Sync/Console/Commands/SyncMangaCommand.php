<?php

namespace Sync\Console\Commands;

use Illuminate\Console\Command;

class SyncMangaCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:manga {manga_uid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will sync the manga';

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
        dispatch((new \Sync\Jobs\IndexMangaJob($this->argument('manga_uid')))->onQueue('sync.index'));
    }
}