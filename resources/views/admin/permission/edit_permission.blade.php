@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Permission</h4>
                  
                  <form class="forms-sample" method="POST" action="{{route('permission_edit')}}">@csrf
                    <div class="form-group">
                      <input type="hidden"  value="{{$permissionDetails->id}}" name="id">
                      <label for="exampleInputUsername1">Permission Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$permissionDetails->name}}" required>
                    </div>
                    <div class="form-group">
                    <label for="exampleFormControlSelect1"> select Module</label>
                    <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="module_id">
                    <option value="{{$permissionDetails->module_id}}">{{$permissionDetails->module->name}}</option>
                    @foreach($modules as $module)
                    <option value="{{$module->id}}">{{$module->name}}</option>
                     @endforeach
                    </select>
                  </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Permission Description</label>
                      <textarea  class="form-control" id="exampleInputEmail1" name="descrition" rows="3">{{$permissionDetails->description}}</textarea>
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