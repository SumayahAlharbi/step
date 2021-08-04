<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\ActionPlan;
use Illuminate\Support\Facades\Mail;
use App\Mail\actionPlanReminderMail;


class actionPlanReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $action_plan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
     public function __construct(ActionPlan $action_plan)
     {
         $this->action_plan = $action_plan;
     }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      //return $this->action_plan->users;
        Mail::to($this->action_plan->users)->send(new actionPlanReminderMail($this->action_plan));
    }
}
