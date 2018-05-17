<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Task;

class ReportsController extends Controller
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
     * method to get daily user reports on tasks assigned, completed, pending and overdue
     * @return view
     */
    public function daily_user_report()
    {
        $user = \Auth::User();
        //get number of tasks assigned, created, completed, overdue
        $assigned_tasks = Task::with('latest_progress')
            ->whereDate('tasks.created_at', Carbon::today())
            ->where('assigned_to', $user->id)
            ->get();

        $completed_tasks = Task::with(['latest_progress' => function($query){
                $query->whereDate('progresses.created_at', Carbon::today());
            }])
            ->whereDate('tasks.created_at', Carbon::today())
            ->where('status', 'closed')
            ->where('assigned_to', $user->id)
            ->get();

        $pending_tasks = Task::with('latest_progress')
            ->whereDate('due_date', '>=', Carbon::now())
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'closed')
            ->get();
        
        $overdue_tasks = Task::with('latest_progress')
            ->whereDate('due_date', '>=', Carbon::now())
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'closed')
            ->get();
                        
        return view('reports.daily_user_report', compact('assigned_tasks', 
            'completed_tasks', 'pending_tasks', 'overdue_tasks'));
    }

    /**
     * method to get weekly user reports on tasks assigned, completed, pending and overdue
     * @return view
     */
    public function weekly_user_report()
    {
        Carbon::setWeekStartsAt(Carbon::MONDAY);
        $user = \Auth::User();
        //get number of tasks assigned, created, completed, overdue
        $assigned_tasks = Task::with('latest_progress')
            ->whereBetween('tasks.created_at', 
                [Carbon::now()->startOfWeek(), Carbon::now()])
            ->where('assigned_to', $user->id)
            ->get();

        $completed_tasks = Task::with(['latest_progress' => function($query){
                $query->whereBetween('progresses.created_at', [Carbon::now()->startOfWeek(), Carbon::now()]);
            }])
            ->where('status', 'closed')
            ->where('assigned_to', $user->id)
            ->get();            
        
        $pending_tasks = Task::with('latest_progress')
            ->whereBetween('tasks.due_date', [Carbon::now(), Carbon::now()->endOfWeek()])
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'closed')
            ->get();
        
        $overdue_tasks = Task::with('latest_progress')
            ->whereBetween('tasks.due_date', [Carbon::now()->startOfWeek(), Carbon::now()])
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'closed')
            ->get();

        return view('reports.weekly_user_report', compact('assigned_tasks', 
            'completed_tasks', 'pending_tasks', 'overdue_tasks'));
    }
}
