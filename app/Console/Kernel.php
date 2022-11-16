<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //Counties
        Commands\Counties::class,
        //vf
        Commands\ViewFacility::class,
        // DhfrSubmission
        Commands\DhfrSubmission::class,
        //Facility
        Commands\Facility::class,
        //partners
        Commands\Partners::class,
        //t_county_target
        Commands\TCountyTarget::class,
        //t_facility hfr target
        Commands\TFacilityHfrTarget::class,
        //weeks
        Commands\Weeks::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        //php artisan etl:Counties  
        $schedule->command('etl:Counties')
        ->everyMinute();
             
                      
        //php artisan etl:facilitys 
        $schedule->command('etl:facilitys')
        ->everyMinute();             
        //php artisan etl:partners  
        $schedule->command('etl:Partners')
        ->everyMinute();             
        //php artisan etl:tcountytarget   
        $schedule->command('etl:tcountytarget')
        ->everyMinute();       
        //php artisan etl:thfrtarget
        $schedule->command('etl:thfrtarget')
        ->everyMinute();             
        //php artisan etl:viewfacility   
        $schedule->command('etl:vf')
            ->everyMinute();
        //php artisan etl:weeks  
        $schedule->command('etl:weeks')
        ->everyMinute();
 
        //php artisan etl:dhfr   
        $schedule->command('etl:dhfr')
        ->everyMinute();  
       
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
