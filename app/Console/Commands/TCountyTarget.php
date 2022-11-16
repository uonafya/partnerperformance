<?php

namespace App\Console\Commands;

use App\Etl\Models\TCountyTargetEtl;
use App\Models\TCountyTarget as ModelsTCountyTarget;
use Illuminate\Console\Command;

class TCountyTarget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:tcountytarget';

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
        //


        // Delete Store and Items (via cascade) First.
        TCountyTargetEtl::truncate();

        $this->info("etl:target_hfr_Facility Started");

        $tcounty_etl = new TCountyTargetEtl();
        $tcounty_etl->setConnection('mysql_etl');

        $tcounty_load = ModelsTCountyTarget::all();
        // whereNotNull('id')->paginate(100000);
        // $this->info($facility_load);
        // $this->info($view_f);
        $all_tcounty_load_transform = ModelsTCountyTarget::transform($tcounty_load);
        // $this->info($all_tcounty_load_transform );

        $all_tcounty_load_transform->each(function($item) use ($tcounty_etl) {
            // $this->info(...$item);
            $tcounty_etl->insert($item);

        });

        $this->info("etl:tcountytarget completed");
    }
}
