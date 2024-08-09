@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
<div class="container-fluid">
        @if($modules->count() <= 0)
        <div class="header">
            <h4 align="center">
                
                <strong>MODULES HAS NOT BEEN CREATED </strong>
            </h4>
           
        </div>
                    @else
        <div class="content-wrapper">
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Available System Modules</h4>
                 
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($modules as $module)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{$module->name}}</td>
                          <td>{{$module->description}}</td>
                         
                          <td><a href="{{url('Admin/module_edit/'.$module->id)}}" class="btn btn-info">  Edit </a>
                          <a href="{{url('Admin/module_delete/'.$module->id)}}" class="btn btn-danger" onclick="return confirm('Do you want to delete this?'); ">Delete </a>
                        </td>
</tr>
@endforeach
                       
                      </tbody>
                    </table>
                    {{$modules->links()}}
                  </div>
                </div>
              </div>
            </div>
            </div>

</div>
          @endif
        </div>
       
      @endsection