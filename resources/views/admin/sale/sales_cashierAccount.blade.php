@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
   
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Sales Record</h4>
                        <div>


                            <form action="{{route('sales_cashierAccount')}}" method="GET">
                            
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="date" name="from_date" id="fromDate" class="form-control" >
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="to_date" id="toDate" class="form-control" >
                                    </div>
                                   

                                    <div class="col-md-3">
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Payment Method</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="Card">Card</option>
                                            <option value="Cash">Cash</option>
                                            
                                        </select>
                                    </div>
                                   
                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">Fetch Sales</button>
                                    </div>
                                    </div>
                            </form>
                        </div>
                        @if(isset($sales))
                        <div class="table-responsive">
                            <h6><strong>Total Amount: N{{number_format($totalAmount, 2)}}</strong></h6>
                        <table id="salesResults" class="table table-hover">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>Invoice No.</th>
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
                                       <a class="btn btn-info" href="javascript:void(0);" data-invoice-id="{{ $sale->id }}" title="Print Invoice">
                                                <i class="typcn typcn-printer menu-icon"></i>
                                            </a>
                                       </td>
                                   </tr>
                                   @endforeach

                       </tbody>
                   </table>
                  {{$sales->links()}}
                            
                       @endif
                        </div>
                        <!-- Results will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<!-- Modal  for Receipt-->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Print Receipt</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printReceipt()">Print</button>
            </div>
        </div>
    </div>
</div>
</div>




@endsection
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{url('admin/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{url('admin/js/salesRecords.js') }}"></script>