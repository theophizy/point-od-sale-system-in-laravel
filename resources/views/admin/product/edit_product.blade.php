@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Product</h4>
                  
                  <form class="forms-sample" method="POST" action="{{route('product_edit')}}">@csrf
                    <div class="form-group">
                      <input type="hidden"  value="{{$productDetails->id}}" name="id">
                      <label for="exampleInputUsername1">Product Name</label>
                      <input type="text" class="form-control" id="exampleInputUsername1" name="name" value="{{$productDetails->name}}" >
                    </div>
                    <div class="form-group">
                     
                      <label for="exampleInputUsername1">Product Price</label>
                      <input type="text" class="form-control" id="exampleInputPice" name="price" value="{{$productDetails->price}}" >
                    </div>
                    <div class="form-group"> 
                <label for="exampleInputUsername1">Avaliable Quantity</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="quantity" placeholder="Quantity" required value="{{$productDetails->quantity}}">
              </div>

              <div class="form-group">
                <label for="exampleInputUsername1">Weight (Mg/Ml e.g 500ml) </label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="weight" placeholder="Weight(Mg/Ml)" required value="{{$productDetails->weight}}">
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1">Manufacturer</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="manufacturer" placeholder="Manufacturer" required value="{{$productDetails->manufacturer}}">
              </div>
              
              <div class="form-group">
                <label for="exampleInputUsername1">Manufacture Date</label>
                <input type="date" class="form-control" id="exampleInputUsername1" name="manufacture_date" placeholder="Manufacture Date" required value="{{$productDetails->manufacture_date}}">
              </div>

              <div class="form-group">
                <label for="exampleInputUsername1">Expiry Date</label>
                <input type="date" class="form-control" id="exampleInputUsername1" name="expiry_date" placeholder="Expiry Date" required value="{{$productDetails->expiry_date}}">
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1"> Product Status</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="status">
                <option value="{{$productDetails->status}}">{{$productDetails->status}}</option>
               
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