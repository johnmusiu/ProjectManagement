@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3>Dashboard </h3>
        <h4>{{ Auth::User()->department->name }} Department: All Tasks</h4>
    </div>

    <div class="panel-body">
        
        <!-- table to hold tasks -->
        @include('tasks.show')
        <!-- end table -->
    </div>
</div>
@endsection
