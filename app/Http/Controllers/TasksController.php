<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\User;
use App\Category;
use App\Department;
use App\Notification;
use App\Progress;

class TasksController extends Controller
{
    /**
     * Create a new controller instance.
     * Only authenticated users can access methods
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * render task create view
     * @param none
     * @return View
     */
    public function index()
    {
        $categories = Category::all()->toArray();
        $departments = Department::all()->toArray();
        $users = User::all()->toArray();

        return view('tasks.index', compact('categories', 'departments', 'users'));
    }

    /**
     * Store a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'description' => 'required',
            'access' => 'required',
            'due_date' => 'required',
            'category' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'department' => 'required',
            'users' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/task/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // // create new task object
        $task = new Task();
        $task->name = $request['name'];
        $task->description = $request['description'];
        $task->access = $request['access'];
        $task->due_date = $request['due_date'];
        $task->category_id = $request['category'];
        $task->status = $request['status'];
        $task->priority = $request['priority'];
        $task->user()->associate(\Auth::User()->id);
        $task->save();

        // users to be notified
        if($request['notify_users']){
            foreach($request['notify_users'] as $user_id){
                $notification = new Notification();
                $notification->task_id = $task->id;
                $notification->user_id = $user_id;
                $notification->save();
            }
        }

        // departments to be notified
        foreach($request['department'] as $department_id){
            $notification = new Notification();
            $notification->task_id = $task->id;
            $notification->department_id = $department_id;
            $notification->save();
        }

        // users assigned to perform a certain task
        foreach($request['users'] as $user_id){
            $task->users()->syncWithoutDetaching($user_id);
        }

        $request = $request->all();
        // save file and add file name to db
        if(isset($request['doc_name'])){
            foreach ($request['doc_name'] as $key => $value) {
                $file_name = time(). str_replace(' ', '', $task->name) . '.' . 
                    $value->getClientOriginalExtension();
                // $file_name = $doc->store('task_files');
                DB::table('task_files')->insert([
                    'task_id' => $task->id,
                    'file_url' => $file_name,
                ]);
                $value->move(public_path('task_files'), $file_name);
            }
        }
        session()->flash("message", "Task added successfully");
        return redirect(route('create_task'));
    }

    /**
     * method for editing a certain task
     * @param Task $task
     * @return redirect route
     */
    public function edit(Task $task)
    {
        //update task
        //call create or update?
        return true;
    }

    /**
     * get and display task details
     * @param Task $task
     * @return view
     */
    public function view_details(Task $task)
    {   
        return view('tasks.view_details', compact('task'));
    }

    /**
     * a method to add user follower to task
     * @param Task $task
     * @return redirect back
     */
    public function follow(Task $task)
    {
        if(Notification::where([
            ['user_id', \Auth::User()->id],
            ['task_id', $task->id]
        ])->count() > 0){
            session()->flash("error", "You already follow this task!");
            return back();
        }
        //add follow => user to get notifications on task
        $notification = new Notification();
        $notification->task_id = $task->id;
        $notification->user_id = \Auth::User()->id;
        $notification->save();

        if($notification) session()->flash("message", "Task followed successfully");
        else session()->flash("error", "Task follow failed");
        return redirect(route('view_task', $task->id));
    }

    /**
     * mark task from open to ongoing
     * @param Task $task
     * @return back
     */
    public function mark_ongoing(Task $task)
    {
        $task->status = 'ongoing';
        if($task->save()) session()->flash("message", "Task marked as ongoing.");
        else session()->flash("error", "Failed: Task not marked as ongoing");

        return back();
    }

    /**
     * update task progress
     * @param Task $task
     * @return back
     */
    public function save_progress(Task $task, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:2|max:255',
            'progress' => 'required|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect('/task/'.$task->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $progress = new Progress();
        $progress->task()->associate($task);
        $progress->user()->associate(\Auth::User());
        $progress->message = $request['message'];
        $progress->progress = $request['progress'];
        
        //if progress 100% mark task as complete
        if($request['progress'] == 100)
        {
            $task->status = 'closed';
            $task->save();
        }
        if($progress->save()) session()->flash("message", "Progress saved successfully.");
        else session()->flash("error", "Progress not saved.");

        return back()->withInput();
    }

    /**
     * method for getting user specific tasks 
     */
    public function user_tasks()
    {
        $tasks_created = Task::with(['user' => function ($query){
                $query->where('id', \Auth::User()->id);
            }, 'latest_progress', 'departments', 'users_assigned'])
            ->latest()->get();

        $tasks_assigned = Task::with(['users' => function ($query){
                $query->where('task_user.user_id', \Auth::User()->id);
            }, 'user', 'latest_progress', 'departments', 'users_assigned'])
            ->latest()->get();
        // dd($tasks_assigned);
        return view('tasks.user_tasks', compact('tasks_created', 'tasks_assigned'));
    }
}
