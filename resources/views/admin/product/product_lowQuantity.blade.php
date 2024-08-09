@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
<div class="row">
   
     <div class="col-lg-12 grid-margin stretch-card">
       <div class="card">
         <div class="card-body">
         <div class="d-flex flex-wrap justify-content-between">
           <h4 class="card-title mb-3">Product with quantity less than 20 in stock</h4>

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
                 @foreach($lowQantityProducts as $lowQuantity)

                 <tr>
                   <td>{{ $loop->iteration }}</td>
                   <td>{{$lowQuantity->name}}</td>
                   <td>{{$lowQuantity->quantity}}</td>
                   <td>{{$lowQuantity->sold_in}}</td>
                   <td>{{$lowQuantity->price}}</td>
                   <td>{{$lowQuantity->expiry_date}}</td>

                 </tr>
                 @endforeach

               </tbody>
             </table>
             {{$lowQantityProducts->links()}}
           </div>
         </div>
       </div>
     </div>
    
   </div>
 </div>
</div>
@endsection