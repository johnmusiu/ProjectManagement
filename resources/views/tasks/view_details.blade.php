@extends('layouts.app')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">
    <h4>{{ $task->id .": ". $task->name }}</h4></div>
  <div class="panel-body">
    <div class="text-center">
      <!-- checks who can update progress for a task -->
      @if($task->assigned_to == Auth::User()->id)
        |@if($task->status == 'open')
          <a href="{{ route('mark_ongoing', $task->id) }}" 
            onclick="event.preventDefault();document.getElementById('mark-ongoing').submit();">
            Mark task as ongoing
          </a>
          <form id="mark-ongoing" action="{{ route('mark_ongoing', $task->id) }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
        @elseif($task->status == 'ongoing')
          <a href="" onclick="event.preventDefault();document.getElementById('progress-form').style.display = 'block';"> Add progress </a>
        @elseif($task->status == 'closed')
          <a href=""> Reopen or Reassign Task </a>
        @endif
        |&nbsp You follow this task
      @else
      <!-- if user didnt create task and is not in departments being notified for task -->
      <!-- and doesnt follow task -->
        |@if(Auth::User()->id != $task->user_id && 
            !in_array(Auth::User()->department_id, $task->departments->pluck('id')->all()) &&
            !in_array(Auth::User()->id, $task->followed_by->pluck('id')->all()))
          <a href="{{ route('follow_task', $task->id) }}" 
            onclick="event.preventDefault();document.getElementById('follow-task').submit();">
            Follow task
          </a>
          <form id="follow-task" action="{{ route('follow_task', $task->id) }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
        @else
           You follow this task
        @endif
      @endif
      <!-- a department manager can comment on task if it is created by a member of thei department -->
      @if(\Auth::User()->hasRole("department_manager") && 
          \Auth::User()->department_id == $task->user->department_id)
        <a href="" 
          onclick="event.preventDefault();document.getElementById('comment-form').style.display = 'block';">
          | Comment on task   
        </a>
      @endif |
      <a href="{{ route('create_reminder', $task->id) }}"> Add Reminder |</a>
    </div>
    
    <table class="table table-striped">
      <tbody>
        <tr>
          <td class="text-right">Date created: </td>
          <td> {{ \Carbon\Carbon::parse($task->created_at)->format('d-m-Y') }}</td>
          <td class="text-right">Created by: </td>
          <td> {{ $task->user->name }}</td>
        </tr>
        </tr>
        <tr>
          <td class="text-right">Description: </td>
          <td> {{ $task->description }}</td>
          <td class="text-right">Due Date: </td>
          <td> {{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}
            ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }})
          </td>
        </tr>
        <tr>
          <td class="text-right">Assigned to: </td>
          <td>{{ $user->name }} </td>
          <td class="text-right">Task status: </td>
          <td> {{ $task->status }}</td>
        </tr>
        <tr>
          <td class="text-right">Documents: </td>          
          <td colspan="3">
            @foreach($task_files as $file)
            <a href="{{ route('download_file', $file->file_url) }}">
              {{ $file->file_url }}
            </a>
            @endforeach
          </td>
        </tr>
        <tr>
          <td class="text-center" colspan="4">
            <form id="progress-form" class="form-horizontal" action="{{ route('save_progress', $task->id) }}" 
              method="post" @if(!$errors->count() > 0) style="display: none;" @endif>
              {{ csrf_field() }}
              <div class="form-group">Add Progress</div>

              <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                <label for="message" class="col-md-4 control-label">Message</label>

                <div class="col-md-6">
                  <input id="message" type="text" class="form-control" name="message" value="{{ old('message') }}" required>
                  
                  @if ($errors->has('message'))
                    <span class="help-block">
                      <strong>{{ $errors->first('message') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group{{ $errors->has('progress') ? ' has-error' : '' }}">
                <label for="progress" class="col-md-4 control-label">Task Progress</label>

                <div class="col-md-1">
                  <input type="number" id="progress" class="form-control" name="progress"
                    value="{{ old('progress') }}" min="0" max="100" required>
                  
                  @if ($errors->has('progress'))
                      <span class="help-block">
                          <strong>{{ $errors->first('progress') }}</strong>
                      </span>
                  @endif
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-md-8 col-md-offset-1">
                  <button type="submit" class="btn btn-primary">
                    Save Progress
                  </button>
                </div>
              </div>
              <hr>
            </form>
            @if($task->progresses->count() > 0)
              <table class="table table-striped">
                <thead>
                  <td>Date</td>
                  <td>User</td>
                  <td>Comment</td>
                  <td>Progress</td>
                </thead>
                <tbody>
                  @foreach($task->progresses as $progress)
                    <tr>
                      <td>{{ \Carbon\Carbon::parse($progress->created_at)->format('d-m-Y') }}</td>
                      <td>{{ $progress->user->name }}</td>
                      <td>{{ $progress->message }}</td>
                      <td>{{ $progress->progress }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              No progress made on task yet.
            @endif
          </td>
        </tr>
        <tr>
          <td class="text-center" colspan="4">
            <form id="comment-form" class="form-horizontal" action="{{ route('save_comment', $task->id) }}" 
              method="post" @if(!$errors->count() > 0) style="display: none;" @endif>
              {{ csrf_field() }}
              <div class="form-group">Comment</div>

              <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                <label for="message" class="col-md-4 control-label">Add Comment</label>

                <div class="col-md-6">
                  <input id="message" type="text" class="form-control" name="comment" value="{{ old('comment') }}" required>
                  
                  @if ($errors->has('comment'))
                    <span class="help-block">
                      <strong>{{ $errors->first('comment') }}</strong>
                    </span>
                  @endif
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-md-8 col-md-offset-1">
                  <button type="submit" class="btn btn-primary">
                    Save Comment
                  </button>
                </div>
              </div>
              <hr>
            </form>
          </td>
        </tr> 

      </tbody>
    </table>
  </div>
</div>
@endsection