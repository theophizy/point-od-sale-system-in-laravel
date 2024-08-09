// public/js/sales.js

document.addEventListener('DOMContentLoaded', function() {
    const packsRadio = document.getElementById('packs');
    const unitsRadio = document.getElementById('units');
    const saleItemsContainer = document.getElementById('saleItems');
    
    // Event listener for toggling between selling in packs and units
    packsRadio.addEventListener('change', function() {
        // Display interface for selling in packs
       // Inside the 'change' event listener for packsRadio in sales.js

saleItemsContainer.innerHTML = `
<div class="row">
  <div class="col-md-6"> 
<input type="text" id="barcodeInput" class="form-control" placeholder="Scan barcode here" autofocus >
</div>
<div class="col-md-6"> 
<button id="addScannedProduct" class="btn btn-primary">Add Product</button>
</div></div>
`;

document.getElementById('addScannedProduct').addEventListener('click', function() {

const barcode = document.getElementById('barcodeInput').value;


// Call a function to handle adding the product by barcode

addProductByBarcode(barcode);
document.getElementById('barcodeInput').value = '';
});

function addProductByBarcode(barcode) {
// AJAX call to backend to find product by barcode and add to transaction list
fetch(`/Admin/products/byBarcode/${barcode}`, {
    method: 'GET',
    headers: {
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(product => {
    
    if (product && product.id) {
        updateTransactionList(product);
    } else {
        alert('Product not found!');
    }
})
.catch(error => {
    console.error('Error:', error);
    alert('Failed to retrieve product details.');
});
}

});


    unitsRadio.addEventListener('change', function() {
        // Display interface for selling in units with product search
       // Inside the 'change' event listener for unitsRadio in sales.js

saleItemsContainer.innerHTML = `
<div class="row">
<div class="col-md-8">
<div id="searchProduct">
    <div class="form-group">
        <label for="productName">Search Product</label>
        <div class="input-group">
            <input type="text" class="form-control" id="productName" placeholder="Enter product name">
           
        </div>
        <br>
        <div id="productDropdown" class="dropdown">
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <!-- Product options will be displayed here -->
            </div>
        </div>
    </div>
</div>
</div>
  </div>
`;

// When the user types in the search input field
$('#productName').on('input', function() {
    var searchValue = $(this).val();
    if (searchValue.length > 2) {
        searchProducts(searchValue);
    }
});

// Function to search products by name
function searchProducts(productName) {
    fetch(`/Admin/products/${encodeURIComponent(productName)}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(products => {
        // Clear previous options
        $('#productDropdown').empty();

        // Populate dropdown with search results
        products.forEach(product => {
            $('#productDropdown').append(`<a class="dropdown-item form-control" href="#" data-product-id="${product.id}">${product.name} ${product.weight} ${product.manufacturer}</a>`);
        });

        // Show dropdown
        $('#productDropdown').show();
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// When a product is selected from the dropdown
$(document).on('click', '.dropdown-item', function() {
    var productId = $(this).data('product-id');
    getProductDetails(productId);
});

// Function to get product details and add to transaction list
function getProductDetails(productId) {
    fetch(`/Admin/products/quantity/${productId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(product => {
        if (product && product.id) {
            updateTransactionList(product);
            $('#productName').val('');
            $('#productDropdown').hide();
        } else {
            alert('Product not found or out of stock!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
});

    // Event listener for complete transaction button
    const completeTransactionBtn = document.getElementById('completeTransaction');
    completeTransactionBtn.addEventListener('click', function(event) {
        if (!confirm('Should the Transaction be completed?')) {
            event.preventDefault(); // Prevent the transaction from completing
            return;
        }
        // Implement logic to complete transaction
       
        const items = gatherItemsFromTransactionList();
        const totalPrice = calculateTotalPrice();
        const paymentMethod = paymentMethodDropdown.value;
        if(paymentMethod == ""){
            alert("Pls select a payment method to complete the transaction")
        }
       // console.log("CSRF Token:", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));


        fetch('/Admin/sales', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ items, totalPrice, paymentMethod })
        })
        .then(response => response.json())
        .then(data => {
           // console.log(data.message)
            if (data.success) {
                alert('Transaction Completed Successfully')
                console.log('Sale completed:', data);
                clearTransactionList();
               // document.getElementById('successMessage').style.display = 'block';
                // setTimeout(() => {
                //     document.getElementById('successMessage').style.display = 'none';
                // }, 5000); // Hide success message after 5 seconds
                
                // Optionally open a new window to display and print the receipt
              //  window.open(`/Admin/receipt/${data.sale_id}`, '_blank');

              fetch('/Admin/receipt/' + data.sale_id)
              .then(response => response.text()) // Assuming the server responds with HTML content for the receipt
              .then(receiptHtml => {
                // Load the receipt HTML into the modal body
                document.querySelector('#receiptModal .modal-body').innerHTML = receiptHtml;
                // Show the modal
                $('#receiptModal').modal('show');
              })
              .catch(error => console.error('Error loading receipt:', error));
            

            } else {
                console.error('Transaction failed:');
            }
        })
            // Clear transaction list and show success message
        
        .catch(error => console.error('Error:', error));
    });
});

function printReceipt() {
    var content = document.getElementById('receiptModal').innerHTML;
    var win = window.open('', '', 'height=650,width=900');
    win.document.write('<html><head>');
    
    win.document.write('</head><body >');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
  }

function clearTransactionList() {
    const transactionList = document.getElementById('transactionList');
    transactionList.innerHTML = ''; // Clear the table contents
    const totalPrice = document.getElementById('totalPrice');
    totalPrice.innerHTML = '';

} 

function gatherItemsFromTransactionList() {
    const items = [];
    const itemRows = document.querySelectorAll('tr[data-product-id]'); // Each item row has a data attribute "data-product-id"
    
    itemRows.forEach(row => {
        const id = row.dataset.productId; // Get product ID from data attribute
        const quantity = parseInt(row.querySelector('.item-quantity').value, 10); // Input field for quantity
        items.push({ id, quantity });
    });

    return items;
}




function updateTransactionList(product) {
    const transactionList = document.getElementById('transactionList');
    
    let row = transactionList.querySelector(`tr[data-product-id="${product.id}"]`);
    let quantity = 1;
   
    if (row ) {
      
        quantity = parseInt(row.querySelector('.item-quantity').value, 10) + 1;
        
        row.querySelector('.item-quantity').value = quantity;
    
        
    } else {
        row = document.createElement('tr');
        row.setAttribute('data-product-id', product.id);
        row.innerHTML = `
            <td><input type="text" class="form-control" value="${product.name}  ${product.weight}" readonly></td>
            <td><input type="text"  class="item-price form-control" value="${(product.price)}" readonly></td>
            <td><input type="number"  class="item-quantity form-control" value="${quantity}" min="1" onchange="updateSubtotal(this)"></td>
            
            <td><input type="text" class="item-subtotal form-control" value="${product.price * quantity}" readonly></td>
            <td><button onclick="removeProductFromTransactionList(this)" class="btn btn-danger" title="Remove"><i class="typcn typcn-trash menu-icon"></i></button></td>
        `;
        transactionList.appendChild(row);
    }
    if (quantity > product.quantity) {
        alert('Quantity exceeds available stock!!'+ " "+ product.quantity);
        row.querySelector('.item-quantity').value = 1;
        row.querySelector('.item-subtotal').value = product.price;
        calculateTotalPrice();
        return; // Prevent adding the product to the transaction list
    }
    calculateTotalPrice();
// Function to calculate total price
}


function removeProductFromTransactionList(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotalPrice(); // Update total after removal
}

function calculateTotalPrice() {
    const transactionList = document.getElementById('transactionList');
    let total = 0;
    transactionList.querySelectorAll('tr').forEach(row => {
        const quantity = parseInt(row.querySelector('.item-quantity').value, 10);
        const price = parseFloat(row.querySelector('.item-price').value);
        const subtotal = quantity * price;
        row.querySelector('.item-subtotal').value = subtotal.toFixed(2);
        //row.querySelector('.item-subtotal').textContent = subtotal.toFixed(2);
        total += subtotal;
       
    });
  
   document.getElementById('totalPrice').textContent = total.toFixed(2);
   return total.toFixed(2);
}



function updateSubtotal(input) {
    const row = input.closest('tr');
    const id = row.dataset.productId;
    const quantity = parseInt(input.value, 10);
    const price = parseFloat(row.querySelector('.item-price').textContent);

    // Check if the quantity exceeds the available quantity
    fetch(`/Admin/products/quantity/${id}`)
        .then(response => response.json())
        .then(product => {
            // alert('Available quantity is'+ " "+ product.quantity)
            if (quantity > product.quantity) {
                alert('Quantity exceeds available stock!'+ " "+ product.quantity);
                input.value = 1; 
                row.querySelector('.item-subtotal').textContent = (price).toFixed(2);
                calculateTotalPrice();
               // row.querySelector('.item-subtotal').textContent = (price * quantity).toFixed(2);// Set the input value to the available quantity
                return;
            }

            // Update the subtotal
            row.querySelector('.item-subtotal').textContent = (price * quantity).toFixed(2);
            calculateTotalPrice(); // Function to calculate total price
        })
        .catch(error => console.error('Error:', error));
}

