<?php

namespace App\Console\Commands;

use App\Etl\Models\DHfrSubmissionEtl;
use App\Models\DHfrSubmission as ModelsDHfrSubmission;
use Illuminate\Console\Command;

class DhfrSubmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:dhfr';

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
        // DHfrSubmissionEtl::truncate();

        $this->info("etl:dhfr Started");

        $dhfr_submission_etl = new DHfrSubmissionEtl();
        $dhfr_submission_etl->setConnection('mysql_etl');
        // $view_faciltity_arr = $view_faciltity_etl->first();

        // dd($dhfr_submission_etl);s

        $dhfr_sub = ModelsDHfrSubmission::whereNotNull('id')->paginate(100000);
        // dd($dhfr_sub);
        // dd($dhfr_submission->find(1)); 
        // $this->info(...$dhfr_sub);
        // $this->info($view_f);
        $all_dhfr_submission = ModelsDHfrSubmission::transform($dhfr_sub);
        // $this->info($all_dhfr_submission);
        $all_dhfr_submission->each(function($item) use ($dhfr_submission_etl) {
            // $this->info(...$item);
            $dhfr_submission_etl->insert($item);

        });

        $this->info("etl:dhfr completed");

    }
}
