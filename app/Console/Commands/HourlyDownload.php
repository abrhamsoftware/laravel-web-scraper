<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HourlyDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to download file hourly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        echo "This is my first Test";
    }
}
