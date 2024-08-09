@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
@include('admin.layout.message-display')
    <div class="container-fluid">
        @if($sales->count() <= 0) <div class="header">
            <h4 align="center">

                <strong>NO SALES RECORDS.</strong>
            </h4>

    </div>
    @else
    <div class="content-wrapper">
        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Sales Records</h4>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Staff</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Date</th>
                                        
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                   
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$sale->id}}</td>
                                                <td>{{$sale->admin->name. " ".$sale->admin->phone}}</td>
                                                <td>{{$sale->total_amount}}</td>
                                                <td>{{$sale->payment_method}}</td>
                                                <td>{{$sale->created_at->diffForHumans()}}</td>
                                                <td>
                                                <button id="detailsBtn" class="btn btn-primary details-btn" data-sale-id="{{$sale->id}}">Details</button>
                                                    
                                                </td>
                                            </tr>
                                            @endforeach

                                </tbody>
                            </table>
                            {{$sales->links()}}
                        </div>
                    </div>
                </div>
            </div>



            @endif
        </div>
    </div>


<!-- Modal for sales items -->
<div class="modal" id="salesItemsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sales Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Unit Price </th>
                        </tr>
                    </thead>
                    <tbody id="salesItemsBody">
                        <!-- Sales items will be inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{asset('admin/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{url('admin/js/salesRecords.js')}}"></script>