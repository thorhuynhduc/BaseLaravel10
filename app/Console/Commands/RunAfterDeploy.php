<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunAfterDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:after-deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run some command after deploy success';

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
     */
    public function handle()
    {
        Artisan::call('migrate');
        $this->info(Artisan::output());
        Artisan::call('cache:clear');
        $this->info(Artisan::output());
        Artisan::call('config:cache');
        $this->info(Artisan::output());
    }
}
