@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
<div class="row">
   
<div class="col-lg-12 d-flex grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between">
            <h4 class="card-title mb-3">Expired Products/ Products Expiring in 30 Days</h4>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Selling Method</th>
                  <th>Price</th>
                  <th>Expiry Date</th>


                </tr>
              </thead>
              <tbody>
                @foreach($getExpiringOrExpiredProducts as $expiryProduct)

                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$expiryProduct->name}}</td>
                  <td>{{$expiryProduct->quantity}}</td>
                  <td>{{$expiryProduct->sold_in}}</td>
                  <td>{{$expiryProduct->price}}</td>
                  <td>{{$expiryProduct->expiry_date}}</td>

                </tr>
                @endforeach

              </tbody>
            </table>
            {{$getExpiringOrExpiredProducts->links()}}
          </div>
        </div>
      </div>
    </div>
 </div>
</div>
@endsection