<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
</head>

<body style="font-family: Arial, sans-serif;">

    <div
        style="max-width: 600px; margin: auto; padding: 10px; border: 1px solid rgba(0, 0, 0, .5); border-radius: 10px;">
        <img src="https://csors.online/images/LOGO.jpg"
            alt=""
            width="200"
            style="width: 100px; max-width: 100px;">

        <p>
            @if ($order->payment_status == 'partial')
                Downpayment paid successfully. Please settle the remaining balance before the event date.
            @elseif($order->payment_status == 'paid')
                Order fully paid. Thank you!
            @elseif($order->payment_status == 'cancelled')
                Order cancelled. Thank you!
            @endif
        </p>

        <h3 style="margin: 0">
            <strong>{{ $order->caterer->name }}</strong>
        </h3>
        <p>
            For other concerns, please contact the caterer directly. Thank you!
        </p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">
                    <strong>{{ $order->caterer->name }}</strong><br>
                    Test, Manila, Philippines<br>
                    {{ $order->caterer->phone_number }}<br>
                    {{ $order->caterer->email }}
                </td>
                <td class="text-right"
                    style="border: 1px solid #000; padding: 8px; text-align: right;">
                    <strong>Order #{{ $order->id }}</strong><br>
                    Order Status: {{ $order->order_status }}<br>
                    Payment Status: {{ $order->payment_status }}<br><br>
                    Paid by:<br>
                    {{ $order->user->full_name }}<br>
                    {{ $order->user->email }}
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Quantity</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Name</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Unit Price</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $orderItem)
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $orderItem->quantity }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">
                            {{ $orderItem->orderable_type == 'App\Models\Food'
                                ? $orderItem->orderable->foodDetail->name .
                                    '
                                                                                                                                                                                                                                                                                                                - ' .
                                    $orderItem->orderable->servingType->name
                                : $orderItem->orderable->name }}
                        </td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $orderItem->orderable->price }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $orderItem->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            Subtotal: {{ $order->total_amount - $order->total_amount * 0.12 }}<br>
            Tax: {{ $order->total_amount * 0.12 }}<br>
            Total: {{ $order->total_amount }}<br>
            Delivery Fee: {{ $order->delivery_amount }}<br>
            <strong>Final Amount: {{ $order->final_amount }}</strong><br>
            <small>Tax - 12%</small>
        </div>

        <h3>Payment History</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Type</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Method</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Amount</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Reference</th>
                    <th style="border: 1px solid #000; padding: 8px; background-color: #f2f2f2;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->payments as $payment)
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $payment->type }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $payment->method }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $payment->amount }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $payment->reference_no }}</td>
                        <td style="border: 1px solid #000; padding: 8px;">{{ $payment->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <strong>Total: {{ $order->payments->sum('amount') }}</strong>
        </div>
    </div>

</body>

</html>