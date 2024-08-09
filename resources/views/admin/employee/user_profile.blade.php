@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"> User Profile</h4>
                  
                  <form class="forms-sample">
                    <div class="form-group">
                      
                     
                      <label for="exampleInputUsername1">Surname</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$userProfileDetails->name}}  {{$userProfileDetails->employee->other_name}}" readonly>
                    </div>
                    <div class="form-group">
                    <div class="form-group">
                <label for="exampleInputUsername1"> Email Address</label>
                <input type="email" class="form-control" id="exampleInputUsername1" name="email" value="{{$userProfileDetails->email}}"  readonly>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="phone" value="{{$userProfileDetails->phone}}" readonly>
              </div>
            

              <div class="form-group">
                <label for="exampleFormControlSelect1"> User Status</label>
                <input type="text" class="form-control" id="exampleInputUsername1"  value="{{$userProfileDetails->status}}" readonly>
              
              </div>

              
              <div class="form-group">
                <label for="exampleInputEmail1">Employee Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="address" rows="3" readonly>{{$userProfileDetails->employee->address}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="guarantor_name" value="{{$userProfileDetails->employee->guarantor_name}}" readonly>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="guarantor_phone" value="{{$userProfileDetails->employee->guarantor_phone}}" readonly>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_address" rows="3" readonly>{{$userProfileDetails->employee->guarantor_address}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Place of Work</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_place_work" rows="3" readonly>{{$userProfileDetails->employee->guarantor_place_work}}</textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="contact_person_name" value="{{$userProfileDetails->employee->contact_person_name}}" readonly>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="contact_person_phone" value="{{$userProfileDetails->employee->contact_person_phone}}" readonly>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Contact Person Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="contact_person_address" rows="3" readonly>{{$userProfileDetails->employee->contact_person_address}}</textarea>
              </div>
                    
                    </div>
                        
                   
                  </form>
                </div>
              </div>
            </div>
           
           
           
          
          
            
          
          
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
     
     
      @endsection