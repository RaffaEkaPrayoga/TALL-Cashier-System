<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1, h4, h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            margin: 0;
            line-height: 1.5;
        }
        .store-info, .customer-info {
            margin-bottom: 20px;
        }
        .store-info h1 {
            font-size: 24px;
            margin-bottom: 2px;
        }
        .store-info p {
            font-size: 14px;
            color: #000000;
        }
        .customer-info strong {
            color: #000;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        .table th {
            background-color: #f1f1f1;
            font-weight: bold;
            color: #555;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table-footer td {
            font-weight: bold;
            background-color: #f1f1f1;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .total {
            text-align: right;
        }
        .thank-you {
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="store-info">
            <h1>{{ $store_name }}</h1>
            <h4>{{ $store_address }}</h4>
        </div>

        <div class="customer-info">
            <p><strong>Customer:</strong> {{ $transaction->customer_name }}</p>
            <br>
            <p><strong>Transaction Code:</strong> {{ $transaction->transaction_code }}</p>
            <br>
            <p><strong>Date:</strong> {{ $transaction->created_at->format('d-m-Y') }}</p>
        </div>

        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactionDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp. {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-footer">
                    <td colspan="3" class="total">Subtotal</td>
                    <td>Rp. {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-footer">
                    <td colspan="3" class="total">Paid</td>
                    <td>Rp. {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
                </tr>
                <tr class="table-footer">
                    <td colspan="3" class="total">Change</td>
                    <td>Rp. {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="thank-you">
            <h3>* Thank You for Shopping with Us *</h3>
        </div>
    </div>
</body>
</html>