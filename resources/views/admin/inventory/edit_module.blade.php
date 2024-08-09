@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Module</h4>
                  
                  <form class="forms-sample" method="POST" action="{{route('module_edit')}}">@csrf
                    <div class="form-group">
                      <input type="hidden"  value="{{$moduleDetails->id}}" name="id">
                      <label for="exampleInputUsername1">Module Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$moduleDetails->name}}" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Module Description</label>
                      <textarea  class="form-control" id="exampleInputEmail1" name="descrition" rows="3" placeholder="Module Description">{{$moduleDetails->description}}</textarea>
                    </div>     
                   
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    <button class="btn btn-light" type="reset">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
           
           
           
          
          
            
          
          
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
     
     
      @endsection