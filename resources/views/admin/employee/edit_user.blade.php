@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit User Info</h4>
                  
                  <form class="forms-sample" method="POST" action="{{route('user_edit')}}">@csrf
                    <div class="form-group">
                      <input type="hidden"  value="{{$adminDetails->id}}" name="id">
                      <input type="hidden"  value="{{$adminDetails->employee->id}}" name="employee_id">
                      <label for="exampleInputUsername1">Surname</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$adminDetails->name}}" required>
                    </div>
                    <div class="form-group">
                    <div class="form-group">
                <label for="exampleInputUsername1"> Email Address</label>
                <input type="email" class="form-control" id="exampleInputUsername1" name="email" value="{{$adminDetails->email}}"  required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="phone" value="{{$adminDetails->phone}}" required>
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1"> Grant Access to Dashbaord Analytics?</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="dashboard_access" required>
               
                <option value="{{$adminDetails->dashboard_access}}">{{$adminDetails->dashboard_access}}</option>
                  <option value="YES">YES</option>
                  <option value="NO">NO</option>
               
                </select>
              </div>

              <div class="form-group">
                <label for="exampleFormControlSelect1"> User Status</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="status" required>
               
                <option value="{{$adminDetails->status}}">{{$adminDetails->status}}</option>
                  <option value="ACTIVE">ACTIVE</option>
                  <option value="INACTIVE">INACTIVE</option>
               
                </select>
              </div>

              <div class="form-group">
                <label for="exampleInputUsername1"> Other Names</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="other_name" value="{{$adminDetails->employee->other_name}}" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Employee Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="address" rows="3" required>{{$adminDetails->employee->address}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="guarantor_name" value="{{$adminDetails->employee->guarantor_name}}" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="guarantor_phone" value="{{$adminDetails->employee->guarantor_phone}}" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_address" rows="3" required>{{$adminDetails->employee->guarantor_address}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Place of Work</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_place_work" rows="3">{{$adminDetails->employee->guarantor_place_work}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="contact_person_name" value="{{$adminDetails->employee->contact_person_name}}" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="contact_person_phone" value="{{$adminDetails->employee->contact_person_phone}}" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Contact Person Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="contact_person_address" rows="3" required>{{$adminDetails->employee->contact_person_address}}</textarea>
              </div>
                    
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