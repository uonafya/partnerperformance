<?php

namespace App\Console\Commands;

use App\Etl\Models\PartnersETL;
use App\Models\Partners as ModelsPartners;
use Illuminate\Console\Command;

class Partners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:partners';

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
       //Todo: check if data  , persist what doesn't
       PartnersETL::truncate();

       $this->info("etl:partners Started");

       $partners_etl = new PartnersETL();
       $partners_etl->setConnection('mysql_etl');
       // $view_faciltity_arr = $view_faciltity_etl->first();

       $partners = new ModelsPartners();
       $partners->setConnection('mysql_wr');

       $partners_load = $partners->all();
       
       $this->info("vf Started msql_wr");
       // $this->info($Counties_etl);

       $all_partners_remote_data = ModelsPartners::transform($partners_load);
       
    //    $this->info($all_partners_remote_data);
        
    // Todo persist from etl
    
       $all_partners_remote_data->each(function($item) use ($partners_etl) {
           // $this->info(...$item);
           $partners_etl->insert($item);
       });

       $this->info("etl:partner success");


    }
}
