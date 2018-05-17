<?php

use Illuminate\Database\Seeder;
use App\Task;
use App\User;
use App\Notification;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_sales = User::find(2);
        $user_sales2 = User::find(4);
        $user_it = User::find(1);
        $user_it2 = User::find(3);

        $task_sales = new Task();
        $task_sales->name = 'Contact Sales Team';
        $task_sales->description = 'Get sales information from sales team';
        $task_sales->access = 'private';
        $task_sales->due_date = \Carbon\Carbon::now()->addWeeks(1);
        $task_sales->category_id = 2;
        $task_sales->status = 'open';
        $task_sales->priority = 'medium';
        $task_sales->assigned_to = $user_sales->id;
        $task_sales->user()->associate($user_sales2->id);
        $task_sales->save();

        // users to be notified / for private tasks this is not done
        $notification = new Notification();
        $notification->task_id = $task_sales->id;
        $notification->user_id = $user_sales->id;
        $notification->save();


        // departments to be notified
        $notification = new Notification();
        $notification->task_id = $task_sales->id;
        $notification->department_id = 2;
        $notification->save();

        $task_it = new Task();
        $task_it->name = 'Contact ISP';
        $task_it->description = 'Contact ISP';
        $task_it->access = 'public';
        $task_it->due_date = \Carbon\Carbon::now()->addWeekDay();
        $task_it->category_id = 4;
        $task_it->status = 'open';
        $task_it->priority = 'high';
        $task_it->assigned_to = $user_it2->id;
        $task_it->user()->associate($user_it->id);
        $task_it->save();

        // departments to be notified
        $notification = new Notification();
        $notification->task_id = $task_it->id;
        $notification->department_id = 2;
        $notification->save();
        
        $notification = new Notification();
        $notification->task_id = $task_it->id;
        $notification->department_id = 1;
        $notification->save();
    }
}
