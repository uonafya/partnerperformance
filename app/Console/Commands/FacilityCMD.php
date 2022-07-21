<?php

namespace App\Console\Commands;

use App\Etl\Models\FacilityEtl;
use App\Models\Facility;
use Illuminate\Console\Command;


// use App\Models\ViewFacilitiesEtl;

class FacilityCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:facility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform ETL on Facilities';

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
        // FacilityEtl::truncate();
        FacilityEtl::truncate();

        $this->info("Facility ETL Started");

        $facility_etl = new FacilityEtl();
        $facility_etl->setConnection('mysql');


        $facility = new Facility();
        $facility->setConnection('mysql_wr');


        $all_facility = $facility->all();

        $this->info('Facilities ETL fetching remotely');

        $this->info("Facilities Started fetching on remote msql_wr");
        // $this->info($view_f);
        $all_facility_remote_data = Facility::transform($all_facility);

        $all_facility_remote_data->each(function ($item) use ($facility_etl) {
            // $this->info(...$item);
            $facility_etl->insert($item);
        });


        // $county_etl->insert(...$all_county_remote_data);

        $this->info($all_facility_remote_data);
    }
}
