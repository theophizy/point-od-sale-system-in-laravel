@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
  @include('admin.layout.message-display')
  <div class="container">
    <h2>Inventories</h2>
    <form action="{{ route('inventory_view') }}" method="GET" class="mb-3">
      @csrf
      <div class="row">
        <div class="col-md-3">
          <label for="supplier_id" class="form-label">Supplier</label>
          <select class="form-control" id="supplier_id" name="supplier_id">
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label for="start_date" class="form-label">Start Date</label>
          <input type="date" class="form-control" id="start_date" name="start_date">
        </div>
        <div class="col-md-3">
          <label for="end_date" class="form-label">End Date</label>
          <input type="date" class="form-control" id="end_date" name="end_date">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary">Apply Filter</button>
        </div>
      </div>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Supplier</th>
          <th>Total Amount</th>
          <th>Balance</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($inventories as $inventory)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $inventory->supplier->supplier_name }}</td>
          <td>{{ $inventory->total_amount }}</td>
          <td>{{ $inventory->balance }}</td>

          @php
          $status = $inventory->balance < 1 ? 'Fully Paid' : 'Partly Paid' ; $buttonStyle=$inventory->balance < 1 ? 'btn btn-success' : 'btn btn-danger' ; @endphp <td class="{{ $buttonStyle}}">
              {{$status}}
              </td>
              <td>{{ $inventory->created_at }} </td>
              <td>
                <button class=" btn-info view-details-btn" data-inventory-id="{{ $inventory->id }}" title="View Details"><i class="typcn typcn-eye menu-icon"></i></button>
                @if($inventory->balance > 0)
                <button class="btn-warning pay-supplier-btn" data-inventory-id="{{ $inventory->id }}" data-balance="{{ $inventory->balance }}" title="Pay Supplier"><i class="typcn typcn-briefcase menu-icon"></i></button>
                @endif
                <button class="btn-secondary view-payments-btn" data-inventory-id="{{ $inventory->id }}" title="Payment Details"><i class="typcn typcn-eye menu-icon"></i></button>
              </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $inventories->links() }}

  </div>

  <!-- Inventory Details Modal -->
  <div class="modal fade" id="inventoryDetailsModal" tabindex="-1" aria-labelledby="inventoryDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="inventoryDetailsModalLabel">Inventory Details</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body lg" id="inventoryDetailsContent">
          <!-- Inventory details will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Pay Supplier Modal -->
  <div class="modal fade" id="paySupplierModal" tabindex="-1" aria-labelledby="paySupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paySupplierModalLabel">Pay Supplier</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
          <form id="paySupplierForm" action="{{ route('inventory_pay') }}" method="POST">
            @csrf
            <input type="hidden" id="inventory_id" name="inventory_id">
            <div class="mb-3">
              <label for="balance" class="form-label">Balance</label>
              <input type="text" class="form-control" id="balance" name="balance" readonly>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>

            <div class="mb-3">
              <button type="submit" class="btn btn-success">Pay</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Supplier Payment Details Modal -->
  <div class="modal fade" id="supplierPaymentDetailsModal" tabindex="-1" aria-labelledby="supplierPaymentDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="supplierPaymentDetailsModalLabel">Supplier Payment Details</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body" id="supplierPaymentDetailsContent">
          <!-- Supplier payment details will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
          const inventoryId = this.getAttribute('data-inventory-id');
          viewInventoryDetails(inventoryId);
        });
      });

      document.querySelectorAll('.pay-supplier-btn').forEach(button => {
        button.addEventListener('click', function() {
          const inventoryId = this.getAttribute('data-inventory-id');
          const balance = this.getAttribute('data-balance');
          paySupplier(inventoryId, balance);
        });
      });

      document.querySelectorAll('.view-payments-btn').forEach(button => {
        button.addEventListener('click', function() {
          const inventoryId = this.getAttribute('data-inventory-id');

          viewSupplierPayments(inventoryId);
        });
      });
    });


    // Handle payment form submission via AJAX
    document.getElementById('paySupplierForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      const formData = new FormData(form);

      $.ajax({
        url: form.action,
        method: form.method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          $('#paySupplierModal .modal-body .alert').remove();
          if (response.success) {
            $('#paySupplierModal .modal-body').prepend('<div class="alert alert-success">' + response.message + '</div>');
            document.getElementById('balance').value = response.balance;
          } else {

            $('#paySupplierModal .modal-body').prepend('<div class="alert alert-danger">' + (response.message || 'You are not authorized to perform this operation') + '</div>');
          }
        },
        error: function(xhr) {
          const error = xhr.responseJSON.message || 'An error occurred';
          // Clear existing alerts
          $('#paySupplierModal .modal-body .alert').remove();
          $('#paySupplierModal .modal-body').prepend('<div class="alert alert-danger">' + error + '</div>');
        }
      });
    });




    function viewInventoryDetails(inventoryId) {
      $.ajax({
        url: 'inventories/' + inventoryId,
        method: 'GET',
        success: function(response) {
          $('#inventoryDetailsContent').html(response);
          var inventoryDetailsModal = new bootstrap.Modal(document.getElementById('inventoryDetailsModal'));
          inventoryDetailsModal.show();
        }
      });
    }

    function paySupplier(inventoryId, balance) {
      $('#inventory_id').val(inventoryId);
      $('#balance').val(balance);
      var paySupplierModal = new bootstrap.Modal(document.getElementById('paySupplierModal'));
      paySupplierModal.show();
    }

    function viewSupplierPayments(inventoryId) {
      $.ajax({
        url: 'inventories/' + inventoryId + '/payments',
        method: 'GET',
        success: function(response) {
          $('#supplierPaymentDetailsContent').html(response);
          var supplierPaymentDetailsModal = new bootstrap.Modal(document.getElementById('supplierPaymentDetailsModal'));
          supplierPaymentDetailsModal.show();
        },
        error: function(xhr) {
          if (xhr.status === 403) {
            alert(xhr.responseJSON.message || 'You do not have permission to view this page.');
          } else {
            alert('An error occurred while loading the payment details.');
          }
        }
      });
    }
  </script>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>