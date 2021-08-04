<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ActionPlan;

class actionPlanReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $action_plan;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( ActionPlan $action_plan)
    {
        $this->action_plan = $action_plan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->subject('Action Plan Due Date Reminder')
      ->view('emails.actionPlan.dueDateReminder');
    }
}
