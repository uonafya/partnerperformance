<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\ViewFacilities;

class ViewFacility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vf:etl';

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
        $this->info("vf Started");

        //local
        $vf = ViewFacilities::all();
        //remote

        // $view_faciltity = new ViewFacility();
        // $view_faciltity->setConnection('mysql');
        // $view_f = $view_faciltity->first();

        
        // $this->info($view_f);

    }
}
