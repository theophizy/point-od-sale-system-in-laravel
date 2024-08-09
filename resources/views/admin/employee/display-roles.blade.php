@extends('admin.layout.layout')

@section('content')
<div class="main-panel">

  @include('admin.layout.message-display')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Edit/Asgn Roles for User: {{ $admin->name }}</h4>


            <form action="{{ route('user_asignRole', $admin) }}" method="POST">
              @csrf
              <!-- @method('PUT') -->

              @foreach($roles->chunk(3) as $chunk)
              <div class="row">
              @foreach($chunk as $role)
                <div class="col-md-4">
                 
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="role{{$role->id }}" name="roles[]" value="{{ $role->id }}" {{ $admin->roles->contains($role->id) ? 'checked' : '' }}>
                    <label class="form-check-label" for="role{{ $role->id }}">{{ $role->name }}</label>
                  </div>
                 
                </div>
                @endforeach
                </div>
                @endforeach

           



              <button type="submit" class="btn btn-primary mt-3">Asign Roles</button>
              <button type="reset" class="btn btn-danger mt-3">Cancel </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @endsection