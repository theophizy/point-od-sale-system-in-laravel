@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
<div class="container-fluid">
        @if($adminUsers->count() <= 0)
        <div class="header">
            <h4 align="center">
                
                <strong>No AVAILABLE USERS.</strong>
            </h4>
           
        </div>
                    @else
        <div class="content-wrapper">
          <div class="row">
            
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Available Users</h4>
                 
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Phone</th>
                          <th>Dashboard Access</th>
                          <th>Status</th>
                          <th>Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($adminUsers as $adminuser)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{$adminuser->name}}</td>
                          <td>{{$adminuser->email}}</td>
                          <td>{{$adminuser->phone}}</td>
                          <td>{{$adminuser->dashboard_access}}</td>
                          <td>{{$adminuser->status}}</td>
                         
                          <td>
                          <button class="btn-warning view-details" data-id="{{ $adminuser->id }}" title="View USer Details"> <i class="typcn typcn-eye menu-icon"></i></button>
                          <a href="{{route('user_viewSingle',$adminuser->id)}}" class="btn-info" title="Edit User Data">  <i class="typcn typcn-pencil menu-icon"></i> </a>
                          <!-- <a href="{{url('Admin/permission_delete/'.$adminuser->id)}}" class="btn btn-danger" onclick="return confirm('Do you want to delete this?'); ">Delete </a> -->
                          <a href="{{route('user_displayRoles', $adminuser->id) }}"
                                                class="btn-success" title="Asign Role to the User">
                                                <i class="typcn typcn-leaf menu-icon"></i>
                                            </a>
                                            <a href="{{route('user_resetPassword', $adminuser->id) }}"
                                                class="btn-danger" title="Reset user password to default" onclick="return confirm('Do you want to reset this password to default?')">
                                                <i class="typcn typcn-key menu-icon"></i>
                                            </a>
                        </td>
</tr>
@endforeach
                       
                      </tbody>
                    </table>
                    {{$adminUsers->links()}}
                  </div>
                </div>
              </div>
            </div>
            </div>

        </div>
          
         
          @endif
        </div>
       
<!-- Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
               
            </div>
            <div class="modal-body">
                <!-- User details will be loaded here -->
                <p id="userDetails"></p>
            </div>
        </div>
    </div>
</div>
</div>


      @endsection
      <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
      
<script>
 document.addEventListener('DOMContentLoaded', function () {
  
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function () {
              
                const userId = this.getAttribute('data-id');
                
                viewUserDetails(userId);
            });
        });
    });


    function viewUserDetails(userId) {
        fetch(`users/${userId}`)
            .then(response => response.json())
            .then(data => {
             
                let userDetails = `
                <table class="table table-bordered">
                <tr><th>Other Name:</th><td> ${data.employee.other_name}</td></tr>
                <tr><th> Employee Address:</th><td> ${data.employee.address}</td></tr>
                <tr><th>Guarantor's Name:</th><td>${data.employee.guarantor_name}</td></tr>
                <tr><th>Guarantor Address</th><td> ${data.employee.guarantor_address}</td></tr>
                <tr><th>guarantor Phone</th><td> ${data.employee.guarantor_phone}</td></tr>
                <tr><th>guarantor place of work</th><td> ${data.employee.guarantor_place_work}</td></tr>
                <tr><th>Contact Person</th><td>${data.employee.contact_person_name}</td></tr>
                <tr><th>Contact Person Address</th><td> ${data.employee.contact_person_address}</td></tr>
                <tr><th>guarantor Phone </th><td> ${data.employee.contact_person_phone}</td></tr>
                    </table>
                   
                `;
                document.getElementById('userDetails').innerHTML = userDetails;
                var userDetailsModal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                userDetailsModal.show();
            });
    }
</script>
