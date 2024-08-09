
<script>
   
$(function(){
    

    
   //alert('Yes Yes Yes Good')
  function scanBarcode(barcode) {
     // Fetch product details from the backend using barcode
     fetch(`/products/${barcode}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Product not found');
            }
            return response.json();
        })
        .then(product => {
            // Add fetched product to the transaction list
            addToTransactionList(product);
        })
        .catch(error => {
            res.json('Error fetching product:', error);
            console.error('Error fetching product:', error);
        });
}
Quagga.init({
    inputStream : {
        name : "Live",
        type : "LiveStream",
        target: document.querySelector('#barcode-scanner') // Video element for displaying camera stream
    },
    decoder : {
        readers : ["ean_reader"] // Specify barcode types to be scanned (e.g., EAN)
    }
}, function(err) {
    if (err) {
        console.error('Error initializing Quagga:', err);
        return;
    }
    Quagga.start();
});

// Event listener for successful barcode scan
Quagga.onDetected(function(data) {
    const barcode = data.codeResult.code;
    Quagga.stop();
    scanBarcode(barcode);
});

// Function to handle product search
function searchProduct(productName) {
    fetch(`/products?name=${encodeURIComponent(productName)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Product not found');
            }
            return response.json();
        })
        .then(product => {
            // Add fetched product to the transaction list
            addToTransactionList(product);
        })
        .catch(error => {
            console.error('Error fetching product:', error);
        });
}

// Event listener for barcode scanning input field
document.getElementById('barcode').addEventListener('change', function(event) {
    const barcode = event.target.value;
    scanBarcode(barcode);
});

// Event listener for product search input field
document.getElementById('product-search').addEventListener('change', function(event) {
    const productName = event.target.value;
    searchProduct(productName);
});
// fubction to toggle between sale in packs or units
function toggleMode() {
    const barcodeInput = document.getElementById('barcodeInput');
    const searchInput = document.getElementById('searchInput');
    // Toggle visibility of barcode scanner and search inputs
    barcodeInput.style.display = (barcodeInput.style.display === 'none') ? 'block' : 'none';
    searchInput.style.display = (searchInput.style.display === 'none') ? 'block' : 'none';
}

// Event listener for toggle button
document.getElementById('toggleModeBtn').addEventListener('click', toggleMode);


function addToTransactionList(product) {
    const transactionList = document.getElementById('transaction-list');
    const listItem = document.createElement('li');
    const subtotal = product.price * product.quantity;
    // listItem.innerHTML = `
    //     <span>${product.name}</span>
    //     <span>Price: ${product.price}</span>
    //     <input type="number" value="${product.quantity}" min="1">
    //     <span>Subtotal: ${subtotal}</span> 
    //     <button class="remove-product">Remove</button>
    // `;

 // Add product name
 listItem.innerHTML = `<span>${product.name}</span> `;

// Add price display
listItem.innerHTML += `Price: <span>$${product.price.toFixed(2)}</span> `;

// Add hidden inputs for product_id and price
listItem.innerHTML += `<input type="hidden" name="products[][productId]" value="${product.id}" />`;
listItem.innerHTML += `<input type="hidden" name="products[][price]" value="${product.price}" />`;

// Add quantity input
listItem.innerHTML += `Quantity: <input type="number" name="products[][quantity]" value="1" min="1" />`;
listItem.innerHTML +=  `<span>Subtotal: ${subtotal}</span>`; 


    transactionList.appendChild(listItem);
    updateTotalPrice();
}

// Event listener for remove product button
document.getElementById('transaction-list').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-product')) {
        event.target.parentElement.remove();
        updateTotalPrice();
    }
});

// Function to update total price
function updateTotalPrice() {
    let totalPrice = 0;
    document.querySelectorAll('#transaction-list li').forEach(function(listItem) {
        const price = parseFloat(listItem.querySelector('span:nth-child(2)').textContent.split(':')[1]);
        const quantity = parseFloat(listItem.querySelector('input').value);
        totalPrice += price * quantity;
    });
    document.getElementById('total-price').textContent = totalPrice.toFixed(2);
}

// Event listener for complete transaction button
document.getElementById('complete-transaction').addEventListener('click', function() {
    // Prepare transaction data
    const transactionData = {
        products: [],
        totalPrice: parseFloat(document.getElementById('total-price').textContent)
    };
    document.querySelectorAll('#transaction-list li').forEach(function(listItem) {
        const product = {
            name: listItem.querySelector('span:nth-child(1)').textContent,
            price: parseFloat(listItem.querySelector('span:nth-child(2)').textContent.split(':')[1]),
            quantity: parseFloat(listItem.querySelector('input').value)
        };
        transactionData.products.push(product);
    });
    
    // Send transaction data to the backend (via AJAX)
    fetch('/complete-transaction', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(transactionData)
    })
    .then(response => {
        if (response.ok) {
            // Transaction completed successfully
            console.log('Transaction completed successfully');
            // Clear transaction list
            document.getElementById('transaction-list').innerHTML = '';
            // Reset total price
            document.getElementById('total-price').textContent = '0.00';
        } else {
            // Error completing transaction
            console.error('Error completing transaction:', response.statusText);
        }
    })
    .catch(error => {
        console.error('Error completing transaction:', error);
    });
});


	})
</script>