<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;

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
            ->with(['user', 'latest_progress', 'latest_comment', 'user_assigned'])
            ->latest()->get();

        if(\Auth::User()->hasRole('department_manager')){
            //get all private tasks created by department members
            $tasks_private = Task::where('access', 'private')
                ->with(['user' => function($query){
                    $query->where('users.department_id', 
                        \Auth::User()->department_id);
                },
                'user_assigned', 'latest_progress', 'latest_comment'])
                ->latest()->get();
            //get all private tasks assigned to department members
            $tasks_private_as = Task::where('access', 'private')
                ->with(['user', 'user_assigned'=> function($query){
                    $query->where('id', \Auth::User()->id);
                }
                , 'latest_progress', 'latest_comment'])
                ->latest()->get();

            $tasks_private = $tasks_private->merge($tasks_private_as);

        }else if(\Auth::User()->hasRole('department_member')){
            //private tasks a user is assigned
            $tasks_private = Task::where('access', 'private')
                ->with(['user', 'latest_progress', 'latest_comment', 
                'user_assigned'=> function($query){
                        $query->where('id', \Auth::User()->id);
                    } 
                ])->latest()->get();
            //private tasks a user created
            $tasks_private_p = Task::where('user_id', \Auth::User()->id)
                ->with(['user', 'user_assigned', 'latest_progress', 
                    'latest_comment'])
                ->latest()->get();

            $tasks_private = $tasks_private->merge($tasks_private_p);

        }else{
            session()->flash("error", "Access denied");
            return back();
        }
        $tasks = $tasks_public->merge($tasks_private)->sortByDesc('id');
        $users = User::get();
        return view('home', compact('tasks', 'users'));
    }
}
