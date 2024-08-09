@extends('admin.layout.layout')

@section('content')
<div class="main-panel">   
@include('admin.layout.message-display')
<div class="container">
    <h2><strong>Add Inventory</strong></h2> <br><p>(Note. The Unit price is relative to mode of supply (Suppied In).E.g If the supply is in cartons, the unit cost is the amount
     per carton and vice versa. The Percentage Unit Price is to determine your selling price
     relative to your mode of sale(Sold In) after the system has calculated the prices)</p>
     <br>
    <form action="{{ route('inventory_create') }}" method="POST">
        @csrf
        <di class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select class="form-control" id="supplier_id" name="supplier_id" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                @endforeach
            </select>
           
        <div id="products-container">
            <div class="product-row mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <label for="product_id" class="form-label">Product</label>
                        <select class="form-control" name="products[0][product_id]" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} {{$product->weight}} Sold In-{{$product->sold_in}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity_supplied" class="form-label">Quantity Supplied</label>
                        <input type="number" class="form-control" name="products[0][quantity_supplied]" required>
                    </div>
                    <div class="col-md-2">
                        <label for="quantity_supplied" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" name="products[0][expiry_date]" required>
                    </div>
                    <div class="col-md-2">
                        <label for="unit_cost" class="form-label">Unit Cost</label>
                        <input type="number" step="0.01" class="form-control" name="products[0][unit_cost]" required>
                    </div>
                    <div class="col-md-2">
                        <label for="supplied_in" class="form-label">Supplied In</label>
                        <select class="form-control supplied-in" name="products[0][supplied_in]" required>
                            <option value="unit">Unit</option>
                            <option value="pack">Pack</option>
                            <option value="carton">Carton</option>
                        </select>
                    </div>
                    <div class="col-md-2 units-per-pack-container" style="display: none;">
                        <label for="units_per_pack" class="form-label">Units Per Pack</label>
                        <input type="number" class="form-control" name="products[0][units_per_pack]">
                    </div>
                    <div class="col-md-2 packs-per-carton-container" style="display: none;">
                        <label for="packs_per_carton" class="form-label">Packs Per Carton</label>
                        <input type="number" class="form-control" name="products[0][packs_per_carton]">
                    </div>
                    <div class="col-md-2">
                        <label for="percentage_unit_price" class="form-label">Percentage Unit Price</label>
                        <input type="number" step="0.01" class="form-control" name="products[0][percentage_unit_price]" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-product" title="Remove"><i class="typcn typcn-trash menu-icon"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-4">
                        <label for="unit_cost" class="form-label">Total Amount of Supplied Goods</label>
                        <input type="number" step="0.01" class="form-control" name="total_amount" required>
                    </div>
                    <div class="col-md-4">
                        <label for="unit_cost" class="form-label">Amount Paid To the Supplier</label>
                        <input type="number" step="0.01" class="form-control" name="amount_paid" required>
                    </div>
        </div>
        <br><br>
        <button type="button" class="btn btn-primary" id="add-product">Add Product</button>
        <button type="submit" class="btn btn-success" onclick="return confirm('Kindly reconfirm your entries before saving. Once saved it can not be modified')" >Save Inventory</button>
    </form>
</div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <script>
    $(document).ready(function() {
        function updateFormVisibility(container) {
            const suppliedIn = container.find('.supplied-in').val();
            container.find('.units-per-pack-container').toggle(suppliedIn === 'pack' || suppliedIn === 'carton');
            container.find('.packs-per-carton-container').toggle(suppliedIn === 'carton');
        }

        $('#products-container').on('change', '.supplied-in', function() {
            const container = $(this).closest('.product-row');
            updateFormVisibility(container);
        });

        $('#add-product').on('click', function() {
            const index = $('#products-container .product-row').length;
            const productRow = `
                <div class="product-row mb-3">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-control" name="products[${index}][product_id]" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} {{$product->weight}} {{$product->sold_in}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="quantity_supplied" class="form-label">Quantity Supplied</label>
                            <input type="number" class="form-control" name="products[${index}][quantity_supplied]" required>
                        </div>
                        <div class="col-md-2">
                            <label for="expiry_date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="products[${index}][expiry_date]" required>
                        </div>
                        <div class="col-md-2">
                            <label for="unit_cost" class="form-label">Unit Cost</label>
                            <input type="number" step="0.01" class="form-control" name="products[${index}][unit_cost]" required>
                        </div>
                        <div class="col-md-2">
                            <label for="supplied_in" class="form-label">Supplied In</label>
                            <select class="form-control supplied-in" name="products[${index}][supplied_in]" required>
                                <option value="unit">Unit</option>
                                <option value="pack">Pack</option>
                                <option value="carton">Carton</option>
                            </select>
                        </div>
                        <div class="col-md-2 units-per-pack-container" style="display: none;">
                            <label for="units_per_pack" class="form-label">Units Per Pack</label>
                            <input type="number" class="form-control" name="products[${index}][units_per_pack]">
                        </div>
                        <div class="col-md-2 packs-per-carton-container" style="display: none;">
                            <label for="packs_per_carton" class="form-label">Packs Per Carton</label>
                            <input type="number" class="form-control" name="products[${index}][packs_per_carton]">
                        </div>
                        <div class="col-md-2">
                            <label for="percentage_unit_price" class="form-label">Percentage Unit Price</label>
                            <input type="number" step="0.01" class="form-control" name="products[${index}][percentage_unit_price]" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-product"title="Remove"><i class="typcn typcn-trash menu-icon"></i></button>
                        </div>
                    </div>
                </div>
            `;
            $('#products-container').append(productRow);
        });

        $('#products-container').on('click', '.remove-product', function() {
            $(this).closest('.product-row').remove();
        });

        // Initialize form visibility on page load
        $('#products-container .product-row').each(function() {
            updateFormVisibility($(this));
        });
    });
</script>

      @endsection