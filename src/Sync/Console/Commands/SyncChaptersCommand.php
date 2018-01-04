<?php

namespace Sync\Console\Commands;

use Illuminate\Console\Command;
use Core\Manga\MangaManager;

class SyncChaptersCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:chapters {manga_id}';

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
     * @var MangaManager
     */
    protected $manager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MangaManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $manga = $this->manager->findOneBy(['id' => $this->argument('manga_id')]);

        if (!$manga) {
            $this->error(sprintf("No manga found for %s", $this->argument('manga_id')));
            return;
        }

        dispatch((new \Sync\Jobs\SyncChaptersJob($manga))->onQueue('sync.index'));
    }
}