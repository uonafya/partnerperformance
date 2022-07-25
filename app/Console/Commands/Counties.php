<?php

namespace App\Console\Commands;

use App\Etl\Models\CountyETL;
use App\Models\Counties as ModelsCounties;
use Illuminate\Console\Command;

class Counties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:counties';

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
        CountyETL::truncate();

        $this->info("etl:Counties Started");

        $County_etl_etl = new CountyETL();
        $County_etl_etl->setConnection('mysql_etl');
        // $view_faciltity_arr = $view_faciltity_etl->first();


        $County_etl = new ModelsCounties();
        $County_etl->setConnection('mysql_wr');

        $Counties_etl = $County_etl->all();
        
        $this->info("vf Started msql_wr");
        // $this->info($Counties_etl);

        $all_county_remote_data = ModelsCounties::transform($Counties_etl);
        
        // $this->info($all_county_remote_data);

        $all_county_remote_data->each(function($item) use ($County_etl_etl) {
            // $this->info(...$item);
            $County_etl_etl->insert($item);
        });

        $this->info("etl:Counties success");
    }
}
