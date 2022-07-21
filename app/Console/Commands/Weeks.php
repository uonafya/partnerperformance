<?php

namespace App\Console\Commands;

use App\Etl\Models\WeeksETL;
use App\Models\Weeks as ModelsWeeks;
use Illuminate\Console\Command;

class Weeks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:weeks';

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
            // ViewFacilitiesEtl::truncate();

            $this->info("vf Started");
    
            $weeks_etl = new WeeksETL();
            $weeks_etl->setConnection('mysql_etl');
            // $view_faciltity_arr = $view_faciltity_etl->first();
    
    
            $weeks = new ModelsWeeks();
            $weeks->setConnection('mysql_wr');
    
            $weeks_load = $weeks->all();
            
            $this->info("weeks Started msql_wr");
            $all_weeks_remote_data = ModelsWeeks::transform($weeks_load);
            $this->info($all_weeks_remote_data);
            
            // $all_weeks_remote_data->each(function($item) use ($weeks_etl) {
            //     // $this->info(...$item);
            //     $weeks_etl->insert($item);
    
            // });

    }
}
