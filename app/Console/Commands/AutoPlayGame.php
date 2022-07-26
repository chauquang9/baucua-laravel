<?php

namespace App\Console\Commands;

use App\Events\StartGame;
use Illuminate\Console\Command;

class AutoPlayGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto start and stop game';

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
     * @return int
     */
    public function handle()
    {
        event(new \App\Events\StartGame());
        sleep(10);
        event(new \App\Events\ResultsGame());
    }
}
