<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Reminder;
use App\Task;

class RemindersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * form view for adding reminder
     */
    public function index(Task $task)
    {
        return view('reminders.index', compact('task'));
    }
    /**
     * save reminder
     * @param Request $request
     * @return redirect
     */
    public function save(Request $request, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'reminder_time' => 'required',
            'reminder_date' => 'required',
            'type' => 'required',
            'frequency' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/task/reminder/'.$task_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $reminder = new Reminder();
        $reminder->type = $request['type'];
        $reminder->frequency = $request['frequency'];
        $reminder->reminder_time = $request['reminder_date'] . " " .
            date("H:i:s", strtotime($request['reminder_time']));
        $reminder->user()->associate(\Auth::User()->id);
        $reminder->task()->associate($task_id);
        
        if(!$reminder->save()){
            session()->flash("message", "Reminder not saved");
            return back()->withInput();
        }
        session()->flash("message", "Reminder saved successfully.");
        return redirect(route('view_task', $task_id));
    }
}
