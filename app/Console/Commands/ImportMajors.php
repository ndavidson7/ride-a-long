<?php

namespace App\Console\Commands;

use RoachPHP\Roach;
use App\Spiders\MajorsSpider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class ImportMajors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-majors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and import all majors listed on the University\'s registrar.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Deleting current majors...');

        Schema::disableForeignKeyConstraints();
        \DB::table('majors')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->info('Scraping new majors...');

        Roach::startSpider(MajorsSpider::class);

        $this->info('Done!');
    }
}
