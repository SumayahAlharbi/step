<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\ActionPlan;
use Carbon\Carbon;
use App\Jobs\actionPlanReminderJob;

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
        $today = Carbon::now()->toDateString();
        $action_plan_id = ActionPlan::whereDate('end','=',$today)
        ->where(function ($q) {
              $q->where('approval','=','Draft')
              ->orwhere('approval', '=', '')
              ->orWhereNull('approval')
              ->select('id');
          })->get();
          if (count($action_plan_id)){
            $action_plan = ActionPlan::findOrFail($action_plan_id->first->id->id);
            //actionPlanReminderJob::dispatch($action_plan);
            $schedule->job(new actionPlanReminderJob($action_plan))->dailyAt('10:33');
          }
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
