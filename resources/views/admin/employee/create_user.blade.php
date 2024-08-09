@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
  @include('admin.layout.message-display')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Create Employee</h4>

            <form class="forms-sample" method="POST" action="{{route('user_create')}}">@csrf
              <div class="form-group">
                <label for="exampleInputUsername1"> Surname</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="name" placeholder="Surname" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Email Address</label>
                <input type="email" class="form-control" id="exampleInputUsername1" name="email" placeholder="Email Address" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="phone" placeholder="Phone Number" required>
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1"> Grant Access to Dashbaord Analytics?</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="dashboard_access" required>
                <option></option>
                  <option value="YES">YES</option>
                  <option value="NO">NO</option>
               
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Other Names</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="other_name" placeholder="Other Names" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Employee Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="address" rows="3" placeholder="Employee Home Address"></textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="guarantor_name" placeholder="Guarantor Name" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Guarantor Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="guarantor_phone" placeholder="Guarantor Phone Number" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_address" rows="3" placeholder="Guarantor Home Address" required></textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Guarantor Place of Work</label>
                <textarea class="form-control" id="exampleInputEmail1" name="guarantor_place_work" rows="3" placeholder="Guarantor Place of Work"></textarea>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="contact_person_name" placeholder="Contact Person Name" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1"> Contact Person Phone Number</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="contact_person_phone" placeholder="Contact Person Phone Number" required>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Contact Person Home Address</label>
                <textarea class="form-control" id="exampleInputEmail1" name="contact_person_address" rows="3" placeholder="Contact Person Home Address" required></textarea>
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