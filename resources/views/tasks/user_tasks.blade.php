@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Dashboard </h3>
        <h4>{{ Auth::User()->department->name }} Department: My Tasks</h4>
    </div>
    
    <div class="panel-body">
        
        <!-- table to hold tasks a user has created -->
        @if($tasks_created->count() > 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center"><strong>Tasks I created</strong></caption>
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
                @foreach($tasks_created as $task)
                    @if($task->user)
                        @include('tasks._user_tasks')
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif
        <hr>
        <!-- end table -->
        <!-- table to hold tasks assigned to a user -->
        @if($tasks_assigned->count() > 0)
        <table class="table table-bordered table-striped table-responsive">
            <caption class="text-center"><strong>Tasks I am assigned to</strong></caption>
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
                @foreach($tasks_assigned as $task)
                    @if($task->users->count() > 0)
                        @include('tasks._user_tasks')
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
