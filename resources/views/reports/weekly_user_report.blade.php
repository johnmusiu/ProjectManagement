@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Dashboard </h3>
        <h4>{{ Auth::User()->department->name }} Department: My Weekly Report</h4>
    </div>
    
    <div class="panel-body">
        <div>
            <a href="{{ route('user_tasks') }}"> Back to My Tasks</a>
        </div>
        <hr>
        <p>Tasks assigned: {{ $assigned_tasks->count() }}</p>
        <p>Tasks completed: {{ $completed_tasks->count() }}</p>
        <p>Tasks pending: {{ $pending_tasks->count() }}</p>
        <p>Tasks overdue: {{ $overdue_tasks->count() }}</p>
        <hr>

        @if($assigned_tasks->count() != 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center bg-primary"><strong>Tasks Assigned This Week</strong></caption>
            <thead>
                <td>Task Name</td>
                <td>Due Date</td>
                <td>Status</td>
                <td>% Done</td>
                <td>View</td>
            </thead> 
            <tbody>
                @foreach($assigned_tasks as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                        <td>{{ $task->status }}</td>
                        <td>@if($task->latest_progress)
                                {{ $task->latest_progress->progress }}%
                            @else
                                0%
                            @endif
                        </td>
                        <td><a href="{{ route('view_task', $task->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($completed_tasks->count() != 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center bg-success"><strong>Tasks Completed This Week</strong></caption>
            <thead>
                <td>Task Name</td>
                <td>Due Date</td>
                <td>Status</td>
                <td>% Done</td>
                <td>View</td>
            </thead> 
            <tbody>
                @foreach($completed_tasks as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                        <td>{{ $task->status }}</td>
                        <td>@if($task->latest_progress)
                                {{ $task->latest_progress->progress }}%
                            @else
                                0%
                            @endif
                        </td>
                        <td><a href="{{ route('view_task', $task->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($pending_tasks->count() != 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center bg-warning"><strong>Tasks Pending This Week</strong></caption>
            <thead>
                <td>Task Name</td>
                <td>Due Date</td>
                <td>Status</td>
                <td>% Done</td>
                <td>View</td>
            </thead> 
            <tbody>
                @foreach($pending_tasks as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                        <td>{{ $task->status }}</td>
                        <td>@if($task->latest_progress)
                                {{ $task->latest_progress->progress }}%
                            @else
                                0%
                            @endif
                        </td>
                        <td><a href="{{ route('view_task', $task->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($overdue_tasks->count() != 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center bg-danger"><strong>Tasks Overdue</strong></caption>
            <thead>
                <td>Task Name</td>
                <td>Due Date</td>
                <td>Status</td>
                <td>% Done</td>
                <td>View</td>
            </thead> 
            <tbody>
                @foreach($overdue_tasks as $task)
                    <tr>
                        <td>{{ $task->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                        <td>{{ $task->status }}</td>
                        <td>@if($task->latest_progress)
                                {{ $task->latest_progress->progress }}%
                            @else
                                0%
                            @endif
                        </td>
                        <td><a href="{{ route('view_task', $task->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
