<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Sale #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details td, .details th {
            padding: 8px;
            text-align: left;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Store Name: {{env('APP_NAME')}}</h2>
        <p>Address Line: {{env('STORE_ADDRESS')}}<br>Contact Info: {{env('PHONE_NUMBER')}} </p>
    </div>

    <h3>Receipt - Sale #{{ $sale->id }}</h3>
    <p>Date: {{ $sale->created_at->format('Y-m-d H:i') }}</p>
    <table class="details" width="100%">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->saleItems as $item)
                <tr>
                    <td>{{ $item->product->name }}  {{ $item->product->weight }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price_per_unit }}</td>
                    <td>{{ number_format($item->quantity * $item->price_per_unit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">Total Price:</td>
                <td class="total">{{number_format($sale->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>Payment Method: {{ $sale->payment_method }}</p>

    <p>Thank you for your patronage!</p>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
