<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Core\User\UserManager;

class AppCleanerCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will clean all pending data';

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
        $um = new UserManager();
        $users = $um->getRepository()->getExpiredPendingUsers();

        foreach ($users as $user) {
            $um->delete($user);
        }

        $this->info(sprintf("Deleted %s users", count($users)));
    }
}
