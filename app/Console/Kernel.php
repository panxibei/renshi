<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Renshi\Renshi_jiaban;
use DB;
use Illuminate\Support\Facades\Cache;

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

        $filePath = 'cron.log';

        $schedule->call(function () {

            // 超过一个月自动归档
            try	{
                DB::beginTransaction();
                
                $result = Renshi_jiaban::where('archived', false)
                    ->where('status', 99)
                    // ->where('updated_at', '>', date('Y-m-d H:i:s', time() - 2 * 24 * 60 * 60))
                    ->whereRaw("created_at < NOW() - INTERVAL '2 DAY'")
                    ->update([
                        'archived' => true,
                    ]);

                // $result = 1;
            }
            catch (\Exception $e) {
                // echo 'Message: ' .$e->getMessage();
                DB::rollBack();
                // return 'Message: ' .$e->getMessage();
                dd('Message: ' .$e->getMessage());
                return 0;
            }

            DB::commit();
            Cache::flush();




        // })->dailyAt('13:00');
        // })->everyMinute()
        })->everyFiveMinutes()
        ->name('auto_archive_every_month')
            ->withoutOverlapping()
            ->appendOutputTo($filePath);



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
