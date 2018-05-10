@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(Auth::user()->hasRole('department_manager'))
                        You are logged in as a department manager.
                    @else
                        You are logged in a junior employee.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
