
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  
  document.addEventListener('DOMContentLoaded', function(){
    
        var salesDataElement = document.getElementById('salesData');
        var salesByPaymentMethodPreviousMonth = JSON.parse(salesDataElement.textContent);
        
        var labels = salesByPaymentMethodPreviousMonth.map(function(item) {
            return item.payment_method;
        });

        var data = salesByPaymentMethodPreviousMonth.map(function(item) {
            return item.total_amount;
        });

        var ctx = document.getElementById('previousMonthPieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'] // Customize colors as needed
                }]
            },
            options: {
                responsive: false // Adjust as needed
            }
        });
      });
   