
@extends('layouts.app')

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">
    <h4>{{ $task->id .": ". $task->name }}</h4>
  </div>
  <div class="panel-body">
    <form class="form form-horizontal" action="{{ route('save_reminder', $task->id) }}" method="post">
        {{ csrf_field() }}
        <div class="form-group text-center">Add Reminder</div>

        <div class="form-group form-inline {{ $errors->has('type') ? ' has-error' : '' }}">
            <label for="type" class="col-md-4 control-label">Reminder Type</label>
            <select id="type" class="form-control col-md-4" name="type">
                <option value="once">One time reminder</option>
                <option value="repeated">Repeated reminder</option>
            
                @if($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif
            </select> 
        </div>

        <div class="form-group form-inline{{ $errors->has('frequency') ? ' has-error' : '' }}">
            <label for="frequency" class="col-md-4 control-label">Reminder Frequency</label>
            <select id="frequency" class="form-control col-md-4" name="frequency">
                <option value="hourly">Hourly</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            
                @if($errors->has('frequency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('frequency') }}</strong>
                    </span>
                @endif
            </select> 
        </div>

        <div class="form-group form-inline{{ $errors->has('reminder_date') ? ' has-error' : '' }}">
            <label for="reminder_date" class="col-md-4 control-label">Reminder Date & Time</label>
            
            <div class="col-md-4">
                <input id="reminder_date" min="{{ date('Y-m-d') }}" type="date" class="form-control" 
                    name="reminder_date" value="{{ old('reminder_date', date('Y-m-d')) }}" max={{ $task->due_date }}required>
                <input id="reminder_time" type="time" class="form-control" name="reminder_time" value="{{ old('reminder_time', date('h:i A')) }}" required>

                @if ($errors->has('reminder_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('reminder_date') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                Save Reminder
                </button>
            </div>
        </div>
    </form>
@endsection