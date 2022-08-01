<?php

namespace App\Console\Commands;

use App\Etl\Models\DHfrSubmissionEtl;
use App\Etl\Models\FacilityETL;
use App\Facility as AppFacility;
use App\Models\DHfrSubmission as ModelsDHfrSubmission;
use Illuminate\Console\Command;

class Facility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:facilitys';

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
        FacilityETL::truncate();

        $this->info("etl:Facility Started");

        $facility_etl = new FacilityETL();
        $facility_etl->setConnection('mysql_etl');

        $facility_load = AppFacility::whereNotNull('id')->paginate(100000);
        // $this->info($facility_load);
        // $this->info($view_f);
        $all_facility_transform = AppFacility::transform($facility_load);
        // $this->info($all_facility_transform);

        $all_facility_transform->each(function($item) use ($facility_etl) {
            // $this->info(...$item);
            $facility_etl->insert($item);

        });

        $this->info("etl:facility success");

    }
}
