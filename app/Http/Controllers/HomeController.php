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
        $tasks = Task::where('status', '!=', 'closed')
            ->with(['user', 'users', 'latest_progress'])
            ->get();
        return view('home', compact('tasks'));
    }
}
