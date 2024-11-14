<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .receipt-box {
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
        }

        .header,
        .payment-history {
            width: 100%;
            margin-bottom: 20px;
        }

        .header-table,
        .payment-history-table,
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td,
        .items-table th,
        .items-table td,
        .payment-history-table th,
        .payment-history-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <p>Your order has been updated!</p><br>
    <h1>
        @if ($order->order_status == 'pending')
            Order is now pending. Please wait for the caterer to confirm your order.
        @elseif($order->order_status == 'confirmed')
            Order confirmed. Please settle any pending payments!
        @elseif($order->order_status == 'completed')
            Order completed!
        @elseif($order->order_status == 'cancelled')
            Order cancelled.
        @endif
    </h1>

    <p>
        For other concerns, please contact the caterer directly. Thank you!
    </p>

    <div class="receipt-box">
        <table class="header-table">
            <tr>
                <td>
                    <strong>{{ $order->caterer->name }}</strong><br>
                    {{-- Test, Manila, Philippines<br> --}}
                    {{ $order->caterer->phone_number }}<br>
                    {{ $order->caterer->email }}
                </td>
                <td class="text-right">
                    <strong>Order #{{ $order->id }}</strong><br>
                    Order Status: {{ $order->order_status }}<br>
                    Payment Status: {{ $order->payment_status }}<br><br>
                    Ordered by:<br>
                    {{ $order->user->full_name }}<br>
                    {{ $order->user->email }}
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <th>Quantity</th>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </thead>
            @foreach ($order->orderItems as $orderItem)
                <tr>
                    <td>{{ $orderItem->quantity }}</td>
                    <td>
                        {{ $orderItem->orderable_type == 'App\Models\Food'
                            ? $orderItem->orderable->foodDetail->name . ' - ' . $orderItem->orderable->servingType->name
                            : $orderItem->orderable->name }}
                    </td>
                    <td>{{ $orderItem->orderable->price }}</td>
                    <td>{{ $orderItem->amount }}</td>
                </tr>
            @endforeach
        </table>

        <div class="footer">
            {{-- Subtotal: 0<br>
            Tax: 0<br> --}}
            <strong>Total: {{ $order->total_amount }}</strong><br>
            {{-- <small>Tax - 12%</small> --}}
        </div>

        <h3>Payment History</h3>
        <table class="payment-history-table">
            <thead>
                <th>Type</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Remarks</th>
            </thead>
            @foreach ($order->payments as $payment)
                <tr>
                    <td>{{ $payment->type }}</td>
                    <td>{{ $payment->method }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->reference_no }}</td>
                    <td>{{ $payment->remarks }}</td>
                </tr>
            @endforeach
        </table>

        <div class="footer">
            <strong>Total: {{ $order->payments->sum('amount') }}</strong>
        </div>
    </div>

</body>

</html>
