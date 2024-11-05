<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            width: 80mm;
            padding: 10px;
            background-color: #fff;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .receipt-header p {
            text-align: center;
            margin: 2px 0;
            font-size: 14px;
        }

        .right {
            text-align: right;
        }

        .details {
            margin-top: 20px;
            font-size: 14px;
        }

        .details p {
            margin: 4px 0;
            font-size: 14px;
            line-height: 1.5;
        }

        .details strong {
            font-weight: bold;
        }

        table {
            width: 100%;
            margin-top: 10px;
            font-size: 14px;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            border-bottom: 2px solid #000;
            border-top: 2px solid #000;
            font-weight: bold;
        }

        .tbody tr td {
            padding: 4px 0;
            text-align: left;
            border-bottom: 1px solid #000;
        }

        .totals {
            font-weight: bold;
        }

        .totals td {
            text-align: right;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .note {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receipt-footer p {
            margin: 2px 0;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>

<body>
    <div class="receipt-container">
        <!-- Receipt Header -->
        <div class="receipt-header">
            <h2>MK Traders</h2>
            <p><strong>Address: Hyderabad City </strong></p>
            <p>Phone: 0312-307278-9</p>
        </div>

        <!-- Sale Info -->
        <div class="details">
            <p><strong>Receipt No:</strong> {{ $sale->invoice_no }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('F d, Y h:i A') }}</p>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody class="tbody">
                @foreach(json_decode($sale->item_name) as $key => $item)
                <tr>
                    <td>{{ $item }}</td>
                    <td>{{ json_decode($sale->quantity)[$key] }}</td>
                    <td>{{ json_decode($sale->price)[$key] }}</td>
                    <td>{{ json_decode($sale->total)[$key] }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="totals">
                    <td colspan="3">Total Amount</td>
                    <td>{{ $sale->total_price }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Discount</td>
                    <td>{{ $sale->discount }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Net Total</td>
                    <td><strong>{{ $sale->Payable_amount }}</strong></td>
                </tr>

                <tr class="totals">
                    <td colspan="3">Previous Balance</td>
                    <td>{{ $previous_balance }}</td>
                </tr>
                <tr class="totals">
                    <td colspan="3">Closing Balance</td>
                    <td>{{ $closing_balance }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer Message -->
        <div class="receipt-footer">
            <p class="note">No Refund Without Receipt</p>
            <p>Thank you for shopping with us!</p>
            <p>Developed by Kashan Shaikh <strong>03173859647</strong></p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.location.href = '/all-sales'; // Replace with your redirect URL
            }, 1000);
        }
    </script>

</body>

</html>