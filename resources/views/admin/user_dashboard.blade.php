@extends('admin.layout.layout')

@section('content')

<div class="main-panel">
  <d class="content-wrapper">
    <div class="row">
      <div class="col-md-6"></div>
      <form action="{{ route('user_dashboard') }}" method="GET" class="form-inline">
        <div class="form-row align-items-center">

          <!-- Select Days Field -->
          <div class="col">
            <label for="days">Select Days:</label>
            <select name="days" id="days" class="form-control">
              <option value=""></option>
              <option value="7">Last 7 Days</option>
              <option value="14">Last 14 Days</option>
              <option value="21">Last 21 Days</option>
              <option value="28">Last 28 Days</option>
              <!-- Add other options as needed -->
            </select>
          </div>



          <!-- Select Payment Method Field -->
          <div class="col">
            <label for="payment_method"> Payment Method:</label>
            <select name="payment_method" class="form-control" id="payment_method">
              <option value=""></option>
              <option value="cash">Cash</option>
              <option value="transfer">Transfer</option>
              <option value="card">Card</option>
              <!-- Add other options as needed -->
            </select>
          </div>

          <!-- Submit Button -->
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Apply Filters</button>
          </div>

        </div>
      </form>


    </div>

    <div class="row">
      <div class="col-xl-12 d-flex grid-margin stretch-card">
        <div class="card">
          <div class="card-body">

            <div class="row">
             
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Total Products</h4>
                    <div class="media">
                      <i class="typcn typcn-archive icon-md text-primary d-flex align-self-start mr-2"></i>
                      <div class="media-body">
                        <h3 class="card-text text-primary "><strong>{{$totalProducts}}</strong></h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    @if( $daysSelected )
                    <h4 class="card-title">Your total {{$selectedPaymentMethod}} Sales in the last {{$daysSelected}} days</h4>
                    @elseif($selectedPaymentMethod)
                    <h4 class="card-title">Your total {{$selectedPaymentMethod}} Sales from inception</h4>
                    @elseif($daysSelected && $selectedPaymentMethod)
                    <h4 class="card-title">Total {{$selectedPaymentMethod}} Sales in the last {{$daysSelected}} days</h4>
                    @else
                    <h4 class="card-title">Your Total sales from your start</h4>
                    @endif
                    <div class="media">
                      <i class="typcn typcn-briefcase icon-md text-primary d-flex align-self-start mr-2"></i>
                      <div class="media-body">
                        <h3 class="card-text text-primary "><strong>N{{number_format($totalAmountSales,2)}}</strong></h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


         

              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    @if($daysSelected)
                    <h4 class="card-title">Your last {{$daysSelected}} days sales Data </h4>
                    @elseif($selectedPaymentMethod)
                    <h4 class="card-title">Your last month {{$selectedPaymentMethod}} sales Data </h4>

                    @elseif($daysSelected && $selectedPaymentMethod)
                    <h4 class="card-title">Your last {{$daysSelected}} days {{$selectedPaymentMethod}} sales Data </h4>
                    @else
                    <h4 class="card-title">Your last month sales Data </h4>
                    @endif
                    <div class="media">

                      <div class="media-body">
                        <div id="salesData" style="display: none;">{{ $salesByPaymentMethodPreviousMonth }}</div>
                        <canvas id="previousMonthPieChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Monthly Sales Trend for the current year</h4>
                    <div class="media">

                      <div class="media-body">
                        <div id="salesTrendData" style="display: none;">{{ $salesTrendMonthly }}</div>
                        <canvas id="salesTrendChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>



          </div>
        </div>
      </div>

    </div>

    <div class="row">

      @if($getExpiringOrExpiredProducts->count() <= 0)
       <div class="header">
        <h4 align="center">

          <strong>NO EXPIRED PRODUCTS/EXPIRING PRODUCTS IN 30 DAYS.</strong>
        </h4>

    </div>
    @else
    <div class="col-lg-12 d-flex grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between">
            <h4 class="card-title mb-3">Expired Products/ Products Expiring in 30 Days</h4>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Product Name</th>
                  <th>Quantity</th>
                  <th>Selling Method</th>
                  <th>Price</th>
                  <th>Expiry Date</th>


                </tr>
              </thead>
              <tbody>
                @foreach($getExpiringOrExpiredProducts as $expiryProduct)

                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$expiryProduct->name}}</td>
                  <td>{{$expiryProduct->quantity}}</td>
                  <td>{{$expiryProduct->sold_in}}</td>
                  <td>{{$expiryProduct->price}}</td>
                  <td>{{$expiryProduct->expiry_date}}</td>

                </tr>
                @endforeach

              </tbody>
            </table>
            {{$getExpiringOrExpiredProducts->links()}}
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
  <div class="row">
   
      @if($lowQantityProducts->count() <= 0) <div class="header">
        <h4 align="center">

          <strong>NO SALES RECORDS.</strong>
        </h4>

    </div>
    @else
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between">
              <h4 class="card-title mb-3">Product with quantity less than 20 in stock</h4>

              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Selling Method</th>
                      <th>Price</th>
                      <th>Expiry Date</th>


                    </tr>
                  </thead>
                  <tbody>
                    @foreach($lowQantityProducts as $lowQuantity)

                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{$lowQuantity->name}}</td>
                      <td>{{$lowQuantity->quantity}}</td>
                      <td>{{$lowQuantity->sold_in}}</td>
                      <td>{{$lowQuantity->price}}</td>
                      <td>{{$lowQuantity->expiry_date}}</td>

                    </tr>
                    @endforeach

                  </tbody>
                </table>
                {{$lowQantityProducts->links()}}
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>
    </div>

  </div>



<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->

<!-- partial -->

@endsection

<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script>
  document.addEventListener('DOMContentLoaded', function() {

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
          data: data.map(Number),
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'] // Customize colors as needed
        }]
      },
      options: {
        responsive: true,
        legend: {
          position: 'right'
        },
        // Adjust as needed
        tooltips: {
          enabled: true, // Display the tooltips by default
          callbacks: {
            label: function(tooltipItem, data) {
              var dataset = data.datasets[tooltipItem.datasetIndex];
              var currentValue = dataset.data[tooltipItem.index];
              var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                return previousValue + currentValue;
              }, 0);
              // alert(total)
              var percentage = total !== 0 ? ((currentValue / total) * 100).toFixed(2) : 0;
              return data.labels[tooltipItem.index] + ':' + currentValue.toFixed(2) + ' (' + percentage + '%)';
            }
          }
        }



      }
    });

    // Line chart showing sales trend
    var salesTrendMonthlyElement = document.getElementById('salesTrendData');
    var salesTrendMonthly = JSON.parse(salesTrendMonthlyElement.textContent);
    var monthNames = ['JAN', 'FEB', 'MARCH', 'APRIL', 'MAY', 'JUN', 'JULY', 'AUG', 'SEPT', 'OCT', 'NOV', 'DEC']
    var labels = salesTrendMonthly.map(function(item) {
      var monthNumber = item.month.split('-')[1];
      return monthNames[monthNumber - 1];
    });

    var data = salesTrendMonthly.map(function(item) {
      return item.total_amount;
    });

    var ctx = document.getElementById('salesTrendChart').getContext('2d');
    var salesTrendChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Sales Trend',
          data: data,
          borderColor: ['#FF6384', '#36A2EB', '#FFCE56'], // Customize line color
          borderWidth: 1 // Customize line width
        }]
      },
      options: {
        responsive: true, // Adjust as needed
        legend: {
          position: 'bottom'
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true // Start y-axis from zero
            }
          }]
        }
      }
    });

  });
</script>