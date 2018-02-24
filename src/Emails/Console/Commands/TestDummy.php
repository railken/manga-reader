<?php

namespace Emails\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use Emails\Mail\DummyMail;

class TestDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A smtp test';

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
        Mail::to($this->argument('email'))->send(new DummyMail());
    }
}
