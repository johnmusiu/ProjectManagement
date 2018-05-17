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
    
    <td>
        {{ $task->user->name }}
        @if($task->user->id != Auth::User()->id)
            @if(in_array($task->user->id, Auth::User()->following->pluck('id')->all()))
                <a href="{{ route('unfollow_user', $task->user->id) }}" 
                    onclick="event.preventDefault();document.getElementById('unfollow-user{{ $task->user->id }}').submit();"
                    >
                    Unfollow
                </a>
                <form id="unfollow-user{{ $task->user->id }}" action="{{ route('unfollow_user', $task->user->id) }}" 
                    method="post" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a href="{{ route('follow_user', $task->user->id) }}"
                    onclick="event.preventDefault();document.getElementById('follow-user{{ $task->user->id }}').submit();"
                    >
                    Follow
                </a>
                <form id="follow-user{{ $task->user->id }}" action="{{ route('follow_user', $task->user->id) }}" 
                    method="post" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        @endif
    </td>
    <td>
        {{ $users[($task->assigned_to)-1]->name }}
        @if($task->assigned_to != Auth::User()->id)
            @if(in_array($task->assigned_to, Auth::User()->following->pluck('id')->all()))
                <a href="{{ route('unfollow_user', $task->assigned_to) }}" 
                    onclick="event.preventDefault();document.getElementById('unfollow-user{{ $task->assigned_to }}').submit();"
                    >
                    Unfollow
                </a>
                <form id="unfollow-user{{ $task->assigned_to }}" action="{{ route('unfollow_user', $task->assigned_to) }}" 
                    method="post" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a href="{{ route('follow_user', $task->assigned_to) }}"
                    onclick="event.preventDefault();document.getElementById('follow-user{{ $task->assigned_to }}').submit();"
                    >
                    Follow
                </a>
                <form id="follow-user{{ $task->assigned_to }}" action="{{ route('follow_user', $task->assigned_to) }}" 
                    method="post" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif 
        @endif
    </td>
    <td><a class="btn btn-secondary" href="/task/{{ $task->id }}">View</a></td>
</tr>
