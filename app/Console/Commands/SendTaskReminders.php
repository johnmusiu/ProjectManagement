<?php

namespace App\Console\Commands;

use Mail;
use Illuminate\Console\Command;
use App\Reminder;
use App\Mail\TaskReminder;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendTaskReminders:sendreminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email reminders to users for tasks.';

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
        //fetch reminders due now (this minute)
        $reminders = Reminder::where('reminder_time', date('y-m-d H:i'))
            ->with('user', 'task')
            ->get();
        foreach($reminders as $reminder){
            //send reminder for each
            Mail::to($reminder->user)->send(new TaskReminder($reminder->task));
        }
    }
}
