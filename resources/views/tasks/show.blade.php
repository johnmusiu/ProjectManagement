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
    @if(\Auth::User()->hasRole('department_manager'))
        @if($task->user || $task->users->count() > 0)
            @include('tasks._task')
        @endif
    @elseif(\Auth::User()->hasRole('department_member'))
        @if($task->user && $task->users->count() > 0)
            @include('tasks._task')
        @endif
    @endif
    @endforeach
</tbody>
</table>