@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
        <form method="POST" action="{{ route('user_changePasword') }}">
            @csrf
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" id="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
        </div></div>

        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
     
     
      @endsection