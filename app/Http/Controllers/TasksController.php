<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\User;
use App\Category;
use App\Department;

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
            'users' => 'required'
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

        foreach($request['department'] as $department_id){
            DB::table('task_assignment')->insert([
                'task_id' => $task->id, 
                'department_id' => $department_id,
                'assigned_to' => 'department',
            ]);
        }

        foreach($request['users'] as $user_id){
            DB::table('task_assignment')->insert([
                'task_id' => $task->id, 
                'user_id' => $user_id,
                'assigned_to' => 'member',
            ]);
        }
        $request = $request->all();
        if($request['doc_name']){
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

    public function update()
    {
        return true;
    }
}
