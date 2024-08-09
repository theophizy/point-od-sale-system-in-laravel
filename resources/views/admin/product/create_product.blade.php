@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
<div class="container">
    <h2>Upload Products from an excel file</h2>
    <form action="{{ route('products_upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="excel_file" class="form-label">Upload Excel File</label>
            <input type="file" class="form-control" id="excel_file" name="excel_file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
 <br>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Create Product</h4>

            <form class="forms-sample" method="POST" action="{{route('product_create')}}">@csrf
              <div class="form-group">
                <label for="exampleInputUsername1">Prouct Name</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="name" placeholder="Product Name" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1">Price </label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="price" placeholder="Price" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1">Avaliable Quantity</label>
                <input type="number" class="form-control" id="exampleInputUsername1" name="quantity" placeholder="Quantity" required>
              </div>

              <div class="form-group">
                <label for="exampleInputUsername1">Weight (Mg/Ml e.g 500ml) </label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="weight" placeholder="Weight(Mg/Ml)" required>
              </div>
              <div class="form-group">
                <label for="exampleInputUsername1">Manufacturer</label>
                <input type="text" class="form-control" id="exampleInputUsername1" name="manufacturer" placeholder="Manufacturer" required>
              </div>
              
              <div class="form-group">
                <label for="exampleInputUsername1">Manufacture Date</label>
                <input type="date" class="form-control" id="exampleInputUsername1" name="manufacture_date" placeholder="Manufacture Date" required>
              </div>

              <div class="form-group">
                <label for="exampleInputUsername1">Expiry Date</label>
                <input type="date" class="form-control" id="exampleInputUsername1" name="expiry_date" placeholder="Expiry Date" required>
              </div>
              <div class="form-group">
                <label for="exampleFormControlSelect1"> Select Mode of Sale</label>
                <select class="form-control form-control-lg" id="exampleFormControlSelect1" name="sold_in" required>
                 
                  <option value="PACKS">PACKS</option>
                  <option value="UNITS">UNITS</option>
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