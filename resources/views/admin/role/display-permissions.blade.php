@extends('admin.layout.layout')

@section('content')
<div class="main-panel">

  @include('admin.layout.message-display')
  <div class="content-wrapper">
    <div class="row">   
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Edit Permissions for Role: {{ $role->name }}</h4>


            <form action="{{ route('role_asign_permission', $role) }}" method="POST">
              @csrf
              <!-- @method('PUT') -->
              @php
              $modulesCount = $modules->count();
              $modulesPerRow = 3;
              @endphp

              @for ($i = 0; $i < $modulesCount; $i +=$modulesPerRow) <div class="row mb-4">
                @foreach($modules->slice($i, $modulesPerRow) as $module)
                <div class="col-md-4">
                  <h4>{{ $module->name }}</h4>
                  @foreach($module->permissions as $permission)
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="permission{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                    <label class="form-check-label" for="permission{{ $permission->id }}">{{ $permission->name }}</label>
                  </div>
                  @endforeach
                </div>
                @endforeach
          </div>
          @endfor


          <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
          <button type="reset" class="btn btn-danger mt-3">Cancel </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection