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
                  
                  <form class="forms-sample" method="POST" action="{{route('supplier_edit')}}">@csrf
                    <div class="form-group">
                      <input type="hidden"  value="{{$supplierDetails->id}}" name="id">
                      <label for="exampleInputUsername1">Supplier Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="supplier_name" value="{{$supplierDetails->supplier_name}}" required>
                    </div>
                    <div class="form-group">
                     
                      <label for="exampleInputUsername1">Supplier Email</label>
                      <input type="email" class="form-control" id="exampleInputUsername1" name="supplier_email" value="{{$supplierDetails->supplier_email}}">
                    </div>
                    <div class="form-group">
                     
                      <label for="exampleInputUsername1">Supplier Phone Number</label>
                      <input type="number" class="form-control" id="exampleInputUsername1" name="supplier_phone" value="{{$supplierDetails->supplier_phone}}" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Supplier Address</label>
                      <textarea  class="form-control" id="exampleInputEmail1" name="supplier_address" rows="3">{{$supplierDetails->supplier_address}}</textarea>
                    </div>     
                    <div class="form-group">
                <label for="exampleFormControlSelect1"> Supplier Status</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="status">
                <option value="{{$supplierDetails->status}}">{{$supplierDetails->status}}</option>
               
                  <option value="ACTIVE">ACTIVE</option>
                  <option value="INACTIVE">INACTIVE</option>
                  
                </select>
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