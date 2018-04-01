<?php

namespace Chord\App\Console\Commands;

use Chord\Domain\User\Services\UserCsvService;
use Illuminate\Console\Command;

class GenerateUsersCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_csv:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for generating users csv';

    /**
     * @var UserCsvService
     */
    protected $service;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserCsvService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->service->generateCsv('cron/');
    }
}
