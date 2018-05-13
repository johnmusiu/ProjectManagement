<table class="table table-bordered table-striped table-responsive">
<thead>
    <td>Task Key</td>
    <td>Task Name</td>
    <td>Task Type</td>
    <td>Priority</td>
    <td>Due Date</td>
    <td>Status</td>
    <td>% Done</td>
    <td>Comments</td>
    <td>Created by</td>
    <td>Assigned to</td>
    <td>View</td>
</thead>
<tbody>
    @foreach($tasks as $task)
    <tr>
        <td>{{ $task['id'] }}</td>
        <td>{{ $task['name'] }}</td>
        <td>{{ $task['access'] }}</td>
        <td>{{ $task['priority'] }}</td>
        <td>{{ Carbon\Carbon::parse($task['due_date'])->diffForHumans() }}</td>
        <td>{{ $task['status'] }}</td>
        @if($task->latest_progress != null)
        {{ dd($task->latest_progress)}}
            <td>
                {{ $task->latest_progress->progress }}%
            </td>
            <td>
                {{ $task->latest_progress->message }}
            </td>
        @else
            <td>0%</td>
            <td>No comment yet</td>
        @endif
        
        <td>{{ $task->user->name }}</td>
        <td>
            @if($task->users->count() > 0)
                @foreach($task->users as $user)
                    {{ $user->name }} <br>
                @endforeach
            @endif
        </td>
        <td><a class="btn btn-secondary" href="/task/{{ $task->id }}">View</a></td>
    </tr>
    @endforeach
</tbody>
</table>



@if(Auth::user()->hasRole('department_manager'))
    You are logged in as a department manager.
    <!-- show all public tasks( assigned to or assigned to 
    their department) -->
@else
    You are logged in as a junior employee.
    <!-- show all public tasks  -->
@endif