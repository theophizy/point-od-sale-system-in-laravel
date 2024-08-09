@extends('admin.layout.layout')

@section('content')
<style>
  .form-check {
    display: flex;
    align-items: center; /* This ensures vertical centering of the label with the radio button */
}

.form-check-label {
    margin-left: 0.5rem; /* Provides some space between the radio button and label */
}

</style>
<div class="main-panel">
  @include('admin.layout.message-display')
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Create Sale</h4> 
            <div>
            <div id="successMessage" style="display: none;">
    <p>Transaction completed successfully!</p>
</div>
    <!-- Barcode scanning input field -->
    
    <!-- Barcode scanner input -->
 <!-- Toggle between selling in packs and units -->
 

 <div class="row">
    <div class="col-md-4">
        <div class="form-check">
            <input type="radio" id="packs" name="sellingType" value="packs" class="form-check-input"  autofocus>
            <label for="packs" class="form-check-label">Sell in Packs (Barcode Scanning)</label>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-check">
            <input type="radio" id="units" name="sellingType" value="units" class="form-check-input" style="float: right;">
            <label for="units" class="form-check-label" style="float: left;">Sell in Units (Product Search)</label>
        </div>
    </div>
<div class="col-md-1"></div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="paymentMethodDropdown">Payment Method</label>
            <select name="paymentMethod" class="form-control" id="paymentMethodDropdown" required>
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer</option>
                <option value="Card">Card</option>
            </select>
        </div>
    </div>
</div>


<!-- Sale items -->
<div id="saleItems">
    <!-- Items will be displayed here based on user selection -->
</div>
<br>

<!-- Transaction list -->
<table class="table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Unit Price</th>
            <th>Quantity</th>
            <th>Sub Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="transactionList">
        <!-- Transaction list will be displayed here -->
    </tbody>
</table>

<div>Total Price: N<span id="totalPrice">0.00</span></div>

<!-- Complete transaction button -->
<br><br>
<button id="completeTransaction" class="btn btn-primary">Complete Transaction</button>



    <!-- Button to complete the transaction -->
    </div>
</div>
</div>

    </div>
  </div>
  </div>
  </div>
  <!-- content-wrapper ends -->
  <!-- partial:../../partials/_footer.html -->
<!-- Receipt Modal -->
<div class="modal" id="receiptModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">    
        <h5 class="modal-title">Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- This iframe will display the PDF receipt -->
        <iframe id="receiptFrame" style="width:100%;height:auto;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="printReceipt()">Print</button>
      </div>
    </div>
  </div>
</div>
<script src="{{asset('admin/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{url('admin/js/sales.js') }}"></script>
    
  @endsection