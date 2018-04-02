<?php

namespace Chord\App\Console\Commands;

use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Console\Command;

class CleanupDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:directory {dir=/public/reports/cron}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for removing all files except newest one';

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * Create a new command instance.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        parent::__construct();
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('dir');
        $files = $this->storage->files($path);
        if(count($files) > 1){
            array_pop($files);
            $this->storage->delete($files);
        }
    }
}