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
use App\Notifications\TaskCreated;  
use App\Notifications\TaskCompleted;  
use App\Notifications\TaskCreatedUser;  
use App\Notifications\AssignedTaskCreated;  
use App\Notifications\AssignedTaskCompleted;  
use App\Notifications\TaskMarkedOngoing;
use App\Notifications\TaskProgressUpdated;
use App\Notifications\UserCompletedTask;
use App\Notifications\UserCreatedTask;
use App\Notifications\UserAssignedTask;

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
            'assigned_to' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/task/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // create new task object
        $task = new Task();
        $task->name = $request['name'];
        $task->description = $request['description'];
        $task->access = $request['access'];
        $task->due_date = $request['due_date'];
        $task->category_id = $request['category'];
        $task->status = $request['status'];
        $task->priority = $request['priority'];
        $task->assigned_to = $request['assigned_to'];
        $task->user()->associate(\Auth::User()->id);
        $task->save();

        \Auth::User()->notify(new TaskCreatedUser($task));
        User::find($request['assigned_to'])->notify(new AssignedTaskCreated($task));

        // users to be notified / for private tasks this is not done
        if($request['access'] != 'private')
        {
            //notify those following assigned user on task progress
            $assigned_user = User::find($request['assigned_to']);
            foreach($assigned_user->followers as $follower){
                $follower->notify(new UserAssignedTask($task, $assigned_user));
            }

            //alert followers user created task
            foreach(\Auth::User()->followers as $follower){
                $follower->notify(new UserCreatedTask($task));
            }

            if($request['notify_users']){
                foreach($request['notify_users'] as $user_id){
                    $notification = new Notification();
                    $notification->task_id = $task->id;
                    $notification->user_id = $user_id;
                    $notification->save();
                    //alert task created
                    User::find($user_id)->notify(new TaskCreated($task));
                }
            }
        }

        // departments to be notified
        foreach($request['department'] as $department_id){
            $notification = new Notification();
            $notification->task_id = $task->id;
            $notification->department_id = $department_id;
            $notification->save();
            //check if member not notified, send alert
            $department = Department::find($department_id);
            if($request['access'] == 'private'){
                foreach($department->users as $user){
                    // $user = $department->users->first();
                    if($user->hasRole('department_manager')){
                        // dd($user);
                        $user->notify(new TaskCreated($task));
                    }
                }
            }else{
                foreach($department->users as $user){
                    if($request['notify_users']){
                        if(!in_array($user->id, $request['notify_users']))
                            $user->notify(new TaskCreated($task));
                    }else{
                        $user->notify(new TaskCreated($task));
                    }
                }
            }
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
        $user = User::find($task->assigned_to);
        //get files associated
        $task_files = DB::table('task_files')->where('task_id', $task->id)->get();
        return view('tasks.view_details', compact('task', 'user', 'task_files'));
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
        if(!$task->save()) {
            session()->flash("error", "Failed: Task not marked as ongoing");
            return back();
        }
        session()->flash("message", "Task marked as ongoing.");
        //send email notifications
        //if private alert dept manager
        foreach($task->departments as $department){
            $dept = Department::find($department->id);
            if($task->access == 'private')
            {
                foreach($dept->users as $user){
                    if($user->hasRole('department_manager')){
                        $user->notify(new TaskMarkedOngoing($task));
                    }
                }
            }else{
                foreach($dept->users as $user){
                    $user->notify(new TaskMarkedOngoing($task));
                }
            }
        }
        foreach($task->followed_by as $follower){
            if(!in_array($follower->department_id, $task->departments->pluck('id')->all()))
                $follower->notify(new TaskMarkedOngoing($task));
        }
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
            \Auth::User()->notify(new AssignedTaskCompleted($task));

            if($task->access != 'private'){
                //notify those following assigned user on task completion
                $assigned_user = User::find($task->assigned_to);
                foreach($assigned_user->followers as $follower){
                    $follower->notify(new UserCompletedTask($task, $assigned_user));
                }
            }
        }
        if($progress->save()){
            session()->flash("message", "Progress saved successfully.");
            foreach($task->departments as $department){
                $dept = Department::find($department->id);
                if($task->access == 'private')
                {
                    foreach($dept->users as $user){
                        if($user->hasRole('department_manager')){
                            if($request['progress'] == 100)
                                $user->notify(new TaskCompleted($task));
                            else 
                                $user->notify(new TaskProgressUpdated($task));
                        }
                    }
                }else{
                    foreach($dept->users as $user){
                        $user->notify(new TaskProgressUpdated($task));
                    }
                }
            }
            foreach($task->followed_by as $follower){
                if(!in_array($follower->department_id, $task->departments->pluck('id')->all()))
                    $follower->notify(new TaskMarkedOngoing($task));
            }
        }else session()->flash("error", "Progress not saved.");

        return back()->withInput();
    }

    /**
     * method for getting user specific tasks 
     */
    public function user_tasks()
    { 
        $tasks_created = Task::with(['user' => function ($query){
                $query->where('id', \Auth::User()->id);
            }, 'latest_progress', 'user_assigned'])
            ->latest()->get();
        $tasks_assigned = Task::with(['user_assigned'=> function ($query){
                $query->where('id', \Auth::User()->id);
            }, 'user', 'latest_progress'])
            ->latest()->get();
        $users = User::get();
        return view('tasks.user_tasks', compact('tasks_created', 'tasks_assigned', 'users'));
    }

    /** 
     * method to download task file
     * @param $file_name
     * @return file
     */
    public function download($file)
    {
        $path = public_path().'/task_files/'.$file;
        
        return response()->download($path, $file);
    }
}
