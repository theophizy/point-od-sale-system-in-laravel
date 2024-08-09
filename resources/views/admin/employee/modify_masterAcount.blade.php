@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
        <form method="POST" action="{{ route('user_masterAccounnt') }}">
            @csrf
            <div class="form-group">
                      <label for="exampleInputUsername1">Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$userProfileDetails->name}}" required>
                    </div>
                   
                    <div class="form-group">
                <label for="exampleInputUsername1"> Email Address</label>
                <input type="email" class="form-control" id="exampleInputUsername1" name="email" value="{{$userProfileDetails->email}}"  required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="phone" value="{{$userProfileDetails->phone}}" required>
              </div>
            


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div></div>

        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
     
     
      @endsection