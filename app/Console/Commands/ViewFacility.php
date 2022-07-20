<?php

namespace App\Console\Commands;

use App\Etl\Models\ViewFacilitiesEtl;
use App\Models\ViewFacilities;
use Illuminate\Console\Command;


// use App\Models\ViewFacilitiesEtl;

class ViewFacility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:viewfacility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        ViewFacilitiesEtl::truncate();

        $this->info("vf Started");

        $view_faciltity_etl = new ViewFacilitiesEtl();
        $view_faciltity_etl->setConnection('mysql_etl');
        // $view_faciltity_arr = $view_faciltity_etl->first();


        $view_faciltity = new ViewFacilities();
        $view_faciltity->setConnection('mysql_wr');

        $view_f = $view_faciltity->all();
        
        $this->info("vf Started msql_wr");
        // $this->info($view_f);
        $all_facility_remote_data = ViewFacilities::transform($view_f);
        
        $all_facility_remote_data->each(function($item) use ($view_faciltity_etl) {
            // $this->info(...$item);
            $view_faciltity_etl->insert($item);

        });

        
        // $view_faciltity_etl->insert(...$all_facility_remote_data);

        // $this->info($all_facility_remote_data);

    }   

}
