<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type"
        content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>CSORS Report</title>

    <style>
        #header {
            text-align: center;
        }

        #header h1,
        p {
            margin: 0px;
        }

        h2 {
            margin-bottom: 5px;
            margin-top: 15px;
        }

        ul,
        li {
            margin: 0px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<div id="header">
    <h1>{{ $caterer['name'] }}</h1>
    <p>{{ $caterer['phone_number'] }}</p>
    <p>{{ $caterer['email'] }} </p>
</div>

<div>
    <h2>Reservations</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Order Status</th>
            <th>Payment Status</th>
            <th>Balance</th>
            <th>Total Amount</th>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->full_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M j, Y g:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->start)->format('M j, Y g:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->end)->format('M j, Y g:i A') }}</td>
                    <td>{{ $order->order_status }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>P {{ number_format($order->total_amount - $order->payments->sum('amount'), 2) }}</td>
                    <td>P {{ number_format($order->total_amount, 2) }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Food Categories</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
        </thead>
        <tbody>
            @foreach ($foodCategories as $foodCategory)
                <tr>
                    <td>{{ $foodCategory->id }}</td>
                    <td>{{ $foodCategory->name }}</td>
                    <td>{{ $foodCategory->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Serving Types</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
        </thead>
        <tbody>
            @foreach ($servingTypes as $servingType)
                <tr>
                    <td>{{ $servingType->id }}</td>
                    <td>{{ $servingType->name }}</td>
                    <td>{{ $servingType->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Food Items</h2>
    <table>
        <thead>
            <th>Category</th>
            <th>Name</th>
            <th>Serving Type</th>
            <th>Price</th>
        </thead>
        <tbody>
            @foreach ($foodDetails as $foodDetail)
                @foreach ($foodDetail->servingTypes as $servingType)
                    <tr>
                        <td>{{ $foodDetail->foodCategory->name }}</td>
                        <td>{{ $foodDetail->name }}</td>
                        <td>{{ $servingType->name }}</td>
                        <td>P {{ $servingType->pivot->price }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <h2>Utilities</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Desc</th>
            <th>Price</th>
        </thead>
        <tbody>
            @foreach ($utilities as $utility)
                <tr>
                    <td>{{ $utility->id }}</td>
                    <td>{{ $utility->name }}</td>
                    <td>{{ $utility->description }}</td>
                    <td>P {{ $utility->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Packages</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>Items</th>
            <th>Price</th>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->name }}</td>
                    <td>
                        <ul>
                            @foreach ($package->packageItems as $packageItem)
                                @if ($packageItem->packageable instanceof \App\Models\Food)
                                    <li>{{ $packageItem->packageable->foodDetail->name . ' - ' . $packageItem->packageable->servingType->name }}
                                    </li>
                                @else
                                    <li>{{ $packageItem->packageable->name }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </td>
                    <td>P {{ $package->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


Generated on: {{ now() }}

<body>

</body>

</html>
