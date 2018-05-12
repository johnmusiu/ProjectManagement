@extends('layouts.app')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4> 
      <a href="/category/create">Add a Category</a>
    </h4>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading"><h4>Create Task</h4></div>
  <div class="panel-body">
    <form action="{{ route('save_task') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label for="name" class="col-md-4 control-label">Task Name</label>

        <div class="col-md-6">
          <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
          
          @if ($errors->has('name'))
              <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
              </span>
          @endif
        </div>
      </div>

      <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label for="description" class="col-md-4 control-label">Task Description</label>

        <div class="col-md-6">
          <textarea id="description" class="form-control" rows="10" name="description" value="{{ old('description') }}" required>
          </textarea>
          
          @if ($errors->has('description'))
              <span class="help-block">
                  <strong>{{ $errors->first('description') }}</strong>
              </span>
          @endif
        </div>
      </div>
      
      <div class="form-group form-inline {{ $errors->has('category') ? ' has-error' : '' }}">
        <label for="category" class="col-md-4 control-label">Task Category</label>
        <select id="category" class="form-control" name="category">
          @foreach($categories as $category)
            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
          @endforeach
        
          @if ($errors->has('category'))
              <span class="help-block">
                  <strong>{{ $errors->first('category') }}</strong>
              </span>
          @endif
        </select> 
      </div>

      <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
        <label for="due_date" class="col-md-4 control-label">Date Due</label>

        <div class="col-md-6">
          <input id="due_date" type="date" class="form-control" name="due_date" 
            min="{{ date('Y-m-d') }}" value="{{ old('due_date') }}" required>
          
          @if ($errors->has('due_date'))
              <span class="help-block">
                  <strong>{{ $errors->first('due_date') }}</strong>
              </span>
          @endif
        </div>
      </div>

      <div class="form-group form-inline {{ $errors->has('access') ? ' has-error' : '' }}">
        <label for="access" class="col-md-4 control-label">Access</label>
        <select id="access" class="form-control" name="access">          
          <option value="private">Private</option>
          <option value="public">Public</option>

          @if ($errors->has('access'))
              <span class="help-block">
                  <strong>{{ $errors->first('access') }}</strong>
              </span>
          @endif
        </select> 
      </div>

      <div class="form-group form-inline {{ $errors->has('status') ? ' has-error' : '' }}">
        <label for="status" class="col-md-4 control-label">Status</label>
        <select id="status" class="form-control" name="status">          
          <option value="open">Open</option>
          <option value="ongoing">Ongoing</option>
          
          @if ($errors->has('status'))
              <span class="help-block">
                <strong>{{ $errors->first('status') }}</strong>
              </span>
          @endif
        </select> 
      </div>

      <div class="form-group form-inline {{ $errors->has('priority') ? ' has-error' : '' }}">
        <label for="priority" class="col-md-4 control-label">Priority</label>
        <select id="priority" class="form-control" name="priority">          
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
          
          @if ($errors->has('priority'))
              <span class="help-block">
                <strong>{{ $errors->first('priority') }}</strong>
              </span>
          @endif
        </select> 
      </div>

      <div class="panel panel-default">
        <div class="panel-header text-center"><h5>User and Department Assignments</h5></div>
        <div class="panel-body">
          <table class="table table-striped table-bordered">
            <thead>
              <td>Departments</td>
              <td>Users Assigned</td>
              <td>User Group</td>
            </thead>
            <tbody>
              <td>
                <div class="form-group col-md-10 {{ $errors->has('department') ? ' has-error' : '' }}">
                  <select id="department" data-style="form-control" class="form-control" name="department[]" multiple>
                    @foreach($departments as $department)
                      <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                    @endforeach
                  
                    @if ($errors->has('department'))
                      <span class="help-block">
                        <strong>{{ $errors->first('department') }}</strong>
                      </span>
                    @endif
                  </select> 
                </div>
              </td>

              <td>
                <div class="form-group col-md-10 {{ $errors->has('users') ? ' has-error' : '' }}">
                    <select id="users" data-style="form-control" class="form-control" name="users[]" multiple>
                      @foreach($departments as $department)
                        <optgroup label="{{ $department['name'] }} Department">
                          @foreach($users as $user)
                          @if($user['department_id'] == $department['id'])
                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                          @endif
                          @endforeach
                        </optgroup>
                      @endforeach
                      @if ($errors->has('users'))
                        <span class="help-block">
                          <strong>{{ $errors->first('users') }}</strong>
                        </span>
                      @endif
                    </select> 
                  </div>

              </td>

              <td>
                Coming Soon
              </td>
            </tbody>
          </table>
        </div>
      </div>
      <div class="form-group{{ $errors->has('doc_name') ? ' has-error' : '' }}">
        <label for="doc_name" class="col-md-4 control-label">Upload Instructions</label>

        <div class="col-md-6">
          <input id="doc_name" type="file" class="form-control" name="doc_name[]" value="{{ old('doc_name') }}" multiple>
          
          @if ($errors->has('doc_name'))
              <span class="help-block">
                  <strong>{{ $errors->first('doc_name') }}</strong>
              </span>
          @endif
        </div>
      </div>
      

      <div class="form-group">
        <div class="col-md-8 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Create Task
            </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection