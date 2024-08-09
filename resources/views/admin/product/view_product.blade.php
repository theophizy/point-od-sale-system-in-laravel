@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
    <div class="container-fluid">
        @if($products->count() <= 0) <div class="header">
            <h4 align="center">

                <strong>NO AVAILABLE PRODUCTS.</strong>
            </h4>

    </div>
    @else
    <div class="content-wrapper">
        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Available Products</h4>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Barcode</th>
                                        <th>Quantity</th>
                                        <th>Weight (Mg/Ml)</th>
                                        <th>Manufacturer</th>
                                        <th>Manufacture Date</th>
                                        <th>Expiry Date</th>
                                        <th>Mode of Sale</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    @if($product->quantity < 10) <tr class="table-danger">
                                        @elseif($product->quantity < 20) <tr class="table-warning">
                                            @else
                                            <tr class="table-info">
                                                @endif
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$product->name}}</td>
                                                <td>{{$product->price}}</td>
                                                <td>{{$product->barcode}}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{$product->weight}}</td>
                                                <td>{{$product->manufacturer}}</td>
                                                <td>{{$product->manufacture_date}}</td>
                                                <td>{{$product->expiry_date}}</td>
                                                <td>{{$product->sold_in}}</td>
                                                <td>{{$product->status}}</td>
                                                
                                                <td><a href="{{url('Admin/product_edit/'.$product->id)}}"
                                                        class="btn-info" title="Edit Product Information"> <i class="typcn typcn-pencil menu-icon"></i> </a>
                                                       @if($product->sold_in == "PACKS" && $product->quantity >0)
                                                        <a href="{{url('Admin/print-barcode/'.$product->barcode)}}"
                                                        class="btn-success" title="Print Product Barcode" target="_blank"> <i class="typcn typcn-printer menu-icon"></i> </a>
                                                   @endif
                                                        <a href="{{url('Admin/product_delete/'.$product->id)}}"
                                                        class="btn-danger"
                                                        onclick="return confirm('Do you want to delete this?'); " title="Delete Product"><i class="typcn typcn-trash menu-icon"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach

                                </tbody>
                            </table>
                            {{$products->links()}}
                        </div>
                    </div>
                </div>
            </div>



            @endif
        </div>
    </div>
</div>
@endsection