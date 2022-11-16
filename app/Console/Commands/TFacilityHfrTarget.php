<?php

namespace App\Console\Commands;

use App\Etl\Models\TFacilityHfrTargetEtl;
use App\Etl\Models\WeeksETL;
use App\Models\TFacilityHfrTarget as ModelsTFacilityHfrTarget;
use Illuminate\Console\Command;

class TFacilityHfrTarget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:thfrtarget';

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
        TFacilityHfrTargetEtl::truncate();

        $this->info("etl:target_hfr_Facility Started");

        $tfacility_etl = new TFacilityHfrTargetEtl();
        $tfacility_etl->setConnection('mysql_etl');

        $tfacility_load = ModelsTFacilityHfrTarget::whereNotNull('id')->paginate(100000);
        // $this->info($facility_load);
        // $this->info($view_f);
        $all_tfacility_load_transform = ModelsTFacilityHfrTarget::transform($tfacility_load);
        // $this->info($all_tfacility_load_transform );

        $all_tfacility_load_transform->each(function($item) use ($tfacility_etl) {
            // $this->info(...$item);
            $tfacility_etl->insert($item);

        });

        $this->info("etl:target_hfr_Facility completed");
    }
}
