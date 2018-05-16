<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['department_member', 'department_manager']);

        $tasks_public = Task::where('access', 'public')
            ->with(['user', 'latest_progress', 'latest_comment', 'assigned_to'])
            ->latest()->get();

        if(\Auth::User()->hasRole('department_manager')){
            //get all private tasks created by department members
            $tasks_private = Task::where('access', 'private')
                ->with(['user' => function($query){
                    $query->where('users.department_id', 
                        \Auth::User()->department_id);
                },
                'assigned_to', 'latest_progress', 'latest_comment'])
                ->latest()->get();
            //get all private tasks assigned to department members
            $tasks_private_as = Task::where('access', 'private')
                ->with(['user', 'assigned_to'=> function($query){
                    $query->where('id', \Auth::User()->id);
                }
                , 'latest_progress', 'latest_comment'])
                ->latest()->get();
                
            $tasks_private = $tasks_private->merge($tasks_private_as);

        }else if(\Auth::User()->hasRole('department_member')){
            //private tasks a user is assigned
            $tasks_private = Task::where('access', 'private')
                ->with(['user', 'latest_progress', 'latest_comment', 
                'assigned_to'=> function($query){
                        $query->where('id', \Auth::User()->id);
                    } 
                ])->latest()->get();
            //private tasks a user created
            $tasks_private_p = Task::where('user_id', \Auth::User()->id)
                ->with(['user', 'assigned_to', 'latest_progress', 
                    'latest_comment'])
                ->latest()->get();

            $tasks_private = $tasks_private->merge($tasks_private_p);

        }else{
            session()->flash("error", "Access denied");
            return back();
        }
        $tasks = $tasks_public->merge($tasks_private);

        return view('home', compact('tasks', 'private_tasks', 'public_tasks'));
    }
}
