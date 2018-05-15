<tr>
    <td>{{ $task['id'] }}</td>
    <td>{{ $task['name'] }}</td>
    <td>{{ $task['access'] }}</td>
    <td>{{ $task['priority'] }}</td>
    <td>{{ Carbon\Carbon::parse($task['due_date'])->diffForHumans() }}</td>
    <td>{{ $task['status'] }}</td>
    @if($task->latest_progress != null)
        <td>
            {{ $task->latest_progress->progress }}%
        </td>
    @else
        <td>0%</td>
    @endif
    @if($task->latest_comment != null)
        <td>
            {{ $task->latest_comment->comment }}
        </td>
    @else
        <td>No comment yet</td>
    @endif
    
    <td>{{ $task->user->name }}</td>
    <td>
        @if($task->users_assigned->count() > 0)
            @foreach($task->users_assigned as $user)
                {{ $user->name }} <br>
            @endforeach
        @endif
    </td>
    <td><a class="btn btn-secondary" href="/task/{{ $task->id }}">View</a></td>
</tr>