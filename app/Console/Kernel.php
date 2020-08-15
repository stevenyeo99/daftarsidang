<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Request as CustomRequest;
use App\Enums\RequestStatus;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call('App\Http\Controllers\RequestKPController@rejectExpiredRequests')
                 ->daily();
        $schedule->call('App\Http\Controllers\RequestSkripsiController@rejectExpiredRequests')
                 ->daily();

        // steven add new
        // kp
        // schedule to notify prodi admin for reschedule and update for prodi admin able to reschedule
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangKPController@notifyProdiAdminToRescheduleIfNeed')
            ->daily();
            // ->everyMinute();
        // set schedule end by the expired date
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangKPController@updateNoRevisionOnH2Request')
            ->daily();
            // ->everyMinute();
        // set the penjadwalan already past and it was old data
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangKPController@updatePenjadwalanIntoOldData')
            ->daily();
            // ->everyMinute();
        // set form to be available on participant list table to enter the form each row
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangKPController@scheduleBeritaAcaraForm')
            ->daily();
            // ->everyMinute();
        // set form cannot be open while expired date or if already submitted status completed dont need to show to the participant again
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangKPController@expiredDateForSubmitForm')
            ->daily();
            // ->everyMinute();

        // skripsi
        // schedule to notify prodi admin for reschedule and update for prodi admin able to reschedule
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangSkripsiController@notifyProdiAdminToRescheduleIfNeed')
            ->daily();
            // ->everyMinute();
        // set schedule end by the expired date
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangSkripsiController@updateNoRevisionOnH2Request')
            ->daily();
            // ->everyMinute();
        // set the penjadwalan already past and it was old data
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangSkripsiController@updatePenjadwalanIntoOldData')
            ->daily();
            // ->everyMinute();
        // set form to be available on participant list table to enter the form each row
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangSkripsiController@scheduleBeritaAcaraForm')
            ->daily();
            // ->everyMinute();
        // set form cannot be open while expired date or if already submitted status completed dont need to show to the participant again
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangSkripsiController@expiredDateForSubmitForm')
            ->daily();
            // ->everyMinute();

        // tesis
        // schedule to notify prodi admin for reschedule and update for prodi admin able to reschedule
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangTesisController@notifyProdiAdminToRescheduleIfNeed')
            ->daily();
            // ->everyMinute();
        // set schedule end by the expired date
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangTesisController@updateNoRevisionOnH2Request')
            ->daily();
            // ->everyMinute();
        // set the penjadwalan already past and it was old data
        $schedule->call('App\Http\Controllers\ProdiPenjadwalanSidangTesisController@updatePenjadwalanIntoOldData')
            ->daily();
            // ->everyMinute();
        // set form to be available on participant list table to enter the form each row
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangTesisController@scheduleBeritaAcaraForm')
            ->daily();
            // ->everyMinute();
        // set form cannot be open while expired date or if already submitted status completed dont need to show to the participant again
        $schedule->call('App\Http\Controllers\ProdiBeritaAcaraSidangTesisController@expiredDateForSubmitForm')
            ->daily();
            // ->everyMinute();

        // hard cover kp, skripsi, tesis api to store db every daily
        // on going status
        // validated status
        // first ongoing 
        $schedule->call('App\Http\Controllers\AdminHardcoverKPController@storeOngoingHardcoverKPByAPI')
            // ->daily();
            ->everyMinute();
        $schedule->call('App\Http\Controllers\AdminHardcoverSkripsiController@storeOngoingHardcoverSkripsiByAPI')
            // ->daily();
            ->everyMinute();
        $schedule->call('App\Http\Controllers\AdminHardcoverTesisController@storeOngoingHardcoverTesisByAPI')
            ->daily();
            // ->everyMinute();
        // second validated
        $schedule->call('App\Http\Controllers\AdminHardcoverKPController@storeValidateHardcoverKPByAPI')
            ->daily();
            // ->everyMinute();
        $schedule->call('App\Http\Controllers\AdminHardcoverSkripsiController@storeValidateHardcoverSkripsiByAPI')
            ->daily();
            // ->everyMinute();
        $schedule->call('App\Http\Controllers\AdminHardcoverTesisController@storeValidateHardcoverTesisByAPI')
            ->daily();
            // ->everyMinute();    
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
