 
     $(document).on('click', '.details-btn', function() {
    
        var saleId = $(this).data('sale-id');
        fetchSalesItems(saleId);
    });
    // Function to fetch sales items
    function fetchSalesItems(saleId) {
        fetch(`/Admin/sales/${saleId}/items`)
            .then(response => response.json())
            .then(data => {
                // Clear previous items
                $('#salesItemsBody').empty();
    
                // Populate modal with sales items
                data.forEach(item => {
                    $('#salesItemsBody').append(`
                        <tr>
                        <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.price_per_unit}</td>
                            
                        </tr>
                    `);
                });
    
                // Show modal
                $('#salesItemsModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }


   


     
    $(document).on('click', '.details-btn', function() {
    
        var saleId = $(this).data('sale-id');
        fetchSalesItems(saleId);
    });
    // Function to fetch sales items
    function fetchSalesItems(saleId) {
        fetch(`/Admin/sales/${saleId}/items`)
            .then(response => response.json())
            .then(data => {
                // Clear previous items
                $('#salesItemsBody').empty();
                
                // Populate modal with sales items
                data.forEach(item => {
                    $('#salesItemsBody').append(`
                        <tr>
                        <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.price_per_unit}</td>
                            
                        </tr>
                    `);
                });
    
                // Show modal
                $('#salesItemsModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
          const target = event.target.closest('a[data-invoice-id]'); // Find the closest ancestor which is an a-tag with data-sale-id
          if (target) {
            const saleId = target.getAttribute('data-invoice-id'); // Ensure you are using the correct attribute name
           
            loadReceipt(saleId);
          }
        });
      });
      
      function loadReceipt(saleId) {
        // Assuming you have a modal with an ID of 'receiptModal'
        fetch(`/Admin/receipt/${saleId}`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text(); // Assuming the response is in HTML format
          })
          .then(data => {
            document.querySelector('#receiptModal .modal-body').innerHTML = data;
            $('#receiptModal').modal('show'); // If you're still using Bootstrap's jQuery modal
          })
          .catch(error => {
            console.error('Error loading receipt:', error);
          });
      }
      
      
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

   
    
    