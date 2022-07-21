<?php

namespace App\Console\Commands;

use App\Etl\Models\CountyEtl;
use App\Models\County;
use Illuminate\Console\Command;


// use App\Models\ViewFacilitiesEtl;

class CountyCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:county';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform ETL on Counties';

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

        // Delete Store and Items (via cascade) First.
        CountyEtl::truncate();

        $this->info("County ETL Started");

        $county_etl = new CountyEtl();
        $county_etl->setConnection('mysql');


        $county = new County();
        $county->setConnection('mysql_wr');


        $all_county = $county->all();

        // $this->info('County ETL fetching remotely');

        $this->info("Counties Started fetching on remote msql_wr");
        // $this->info($view_f);
        $all_county_remote_data = County::transform($all_county);

        $all_county_remote_data->each(function ($item) use ($county_etl) {
            // $this->info(...$item);
            $county_etl->insert($item);
        });


        // $county_etl->insert(...$all_county_remote_data);

        $this->info($all_county_remote_data);
    }
}
