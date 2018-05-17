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
        @if($task->access == 'private')
            @if(Auth::User()->hasRole('department_manager'))
                @if(Auth::User()->id == $task->assigned_to || Auth::User()->id == $task->user->id ||
                    Auth::User()->department_id == $task->user->department_id)
                    @include('tasks._show')
                @endif
            @else
                @if(Auth::User()->id == $task->assigned_to || Auth::User()->id == $task->user->id)
                    @include('tasks._show')
                @endif
            @endif
        @else
            @include('tasks._show')
        @endif
    @endforeach
</tbody>
</table>