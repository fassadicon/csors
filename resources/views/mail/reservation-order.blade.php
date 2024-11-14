<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            background-color: #f5f5f5;
        }

        .receipt-box {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .header h3 {
            color: #277F71;
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        p,
        h4 {
            color: #333;
            margin: 0 0 8px;
        }

        .header-table,
        .items-table,
        .payment-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .header-table td {
            padding: 8px;
            vertical-align: top;
        }

        .header-table td:nth-child(2) {
            text-align: right;
        }

        .header-table strong {
            font-size: 1.2em;
            color: #277F71;
        }

        h4 {
            color: #EB862A;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .items-table th,
        .payment-history-table th {
            background-color: #277F71;
            color: #fff;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .items-table td,
        .payment-history-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            /* display: flex;
            flex-direction: row; */
            /* align-items: flex-end; */
            margin-top: 20px;
        }

        .footer strong {
            
            margin: 4px 0;
            font-size: 1.1em;
            color: #277F71;
        }

        .promo {
            color: #EB862A;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="receipt-box">
        <img src="https://csors.online/images/LOGO.jpg" alt="Logo" width="100"
            style="max-width: 100px; margin-bottom: 15px;">

        <div class="header">
            <h3>Order Reservation</h3>
            <p><strong>Date Ordered:</strong> {{$created_at}}</p>
            <p><strong>Date Start:</strong> {{$start}}</p>
            <p><strong>Date End:</strong> {{$end}}</p>
            <p>For other concerns, please contact the caterer directly. Thank you!</p>
        </div>

        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <strong>{{ $caterer->name }}</strong><br>
                        {{-- Test, Manila, Philippines<br> --}}
                        {{ $caterer->phone_number }}<br>
                        {{ $caterer->email }}
                    </td>
                    <td>
                        <strong>Order #{{ $order->id }}</strong><br>
                        Order Status: {{ $order->order_status }}<br>
                        Payment Status: {{ $order->payment_status }}<br><br>
                        Ordered by:<br>
                        {{ $order->user->full_name }}<br>
                        {{ $order->user->email }}
                    </td>
                </tr>
            </table>
        </div>

        <h4>Order Details</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Name</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>

        <div class="footer">
            <strong>Total: {{ number_format($order->total_amount + $order->deducted_amount , 2) }}</strong>
        </div>

        <h4>Payment History</h4>
        <table class="payment-history-table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->payments as $payment)
                <tr>
                    <td>{{ $payment->type }}</td>
                    <td>{{ $payment->method }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->reference_no }}</td>
                    <td>{{ $payment->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            @php
                $subtotalMinusPromo = 0;
                $promoValue;
                if($order->promo) {
                    // $subtotalMinusPromo = ($order->total_amount - $order->promo->value);
                    // check if fixed
                    if($order->promo->type === 'fixed') {
                        $subtotalMinusPromo = ($order->total_amount);
                        $promoValue = $order->promo->value;
                    } // check if percentage
                    else if($order->promo->type === 'percentage') {
                        $totalPromo = $order->total_amount * ($order->promo->value / 100);
                        $promoValue = $totalPromo;
                        $subtotalMinusPromo = $order->total_amount;
                    }
                } else {
                    $subtotalMinusPromo = ($order->total_amount);
                }
            @endphp
            <p>Subtotal: Php {{ number_format($order->total_amount + $order->deducted_amount , 2) }}</p>
            @if ($order->promo)
                <p class="text-sm italic">Subtotal: Php {{ number_format($order->total_amount+ $order->deducted_amount , 2) }} - <b
                        class="text-jt-primary">Php {{ number_format($order->deducted_amount, 2) }}</b></p>
                {{-- <p class="text-sm italic">New Subtotal: Php {{ number_format($subtotalMinusPromo, 2) }}</p> --}}
            @endif
            <p class="text-sm italic">Tax: Php {{ number_format($subtotalMinusPromo * 0.12, 2) }}</p>
            <p class="text-sm italic">Delivery Fee: Php {{ number_format($order->delivery_amount, 2) }}</p>
            <h4>Total Amount: Php {{ number_format($subtotalMinusPromo + $order->delivery_amount, 2)
                }}</h4>
        </div>
    </div>
</body>

</html>