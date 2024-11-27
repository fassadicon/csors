<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type"
        content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">
    <title>CSORS Report</title>

    <style>
        .header {
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
<div class="header">
    <h1>CSORS</h1>
    <p>{{ auth()->user()->phone_number }}</p>
    <p>{{ auth()->user()->email }} </p>
</div>

<div>
    <h2>Caterers</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>User</th>
            <th>Name</th>
            <th>CP #</th>
            <th>Email</th>
            <th>Verified At</th>
        </thead>
        <tbody>
            @foreach ($caterers as $caterer)
                <tr>
                    <td>{{ $caterer->id }}</td>
                    <td>{{ $caterer->user->full_name }} {{ $caterer->user->phone_number }} {{ $caterer->user->email }}
                    </td>
                    <td>{{ $caterer->name }}</td>
                    <td>{{ $caterer->phone_number }}</td>
                    <td>{{ $caterer->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($caterer->updated_at)->format('M j, Y g:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Customers</h2>
    <table>
        <thead>
            <th>Id</th>
            <th>Name</th>
            <th>CP #</th>
            <th>Email</th>
            <th>Verified At</th>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->updated_at)->format('M j, Y g:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($selectedCaterer != null)
    <div class="header">
        <h1>{{ $selectedCaterer['name'] }}</h1>
        <p>{{ $selectedCaterer['phone_number'] }}</p>
        <p>{{ $selectedCaterer['email'] }} </p>
    </div>
@endif

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
                @if ($order->user)
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
                @endif
            @endforeach
        </tbody>
    </table>

    <h2>Food Categories</h2>
    <table>
        <thead>
            <th>Id</th>
            @if ($selectedCaterer == null)
                <th>Caterer</th>
            @endif
            <th>Name</th>
            <th>Description</th>
        </thead>
        <tbody>
            @foreach ($foodCategories as $foodCategory)
                <tr>
                    <td>{{ $foodCategory->id }}</td>
                    @if ($selectedCaterer == null)
                        <td>{{ $foodCategory->caterer->name }}</td>
                    @endif
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
            @if ($selectedCaterer == null)
                <th>Caterer</th>
            @endif
            <th>Name</th>
            <th>Description</th>
        </thead>
        <tbody>
            @foreach ($servingTypes as $servingType)
                <tr>
                    <td>{{ $servingType->id }}</td>
                    @if ($selectedCaterer == null)
                        <td>{{ $servingType->caterer->name }}</td>
                    @endif
                    <td>{{ $servingType->name }}</td>
                    <td>{{ $servingType->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Food Items</h2>
    <table>
        <thead>
            @if ($selectedCaterer == null)
                <th>Caterer</th>
            @endif
            <th>Category</th>
            <th>Name</th>
            <th>Serving Type</th>
            <th>Price</th>
        </thead>
        <tbody>
            @foreach ($foodDetails as $foodDetail)
                @foreach ($foodDetail->servingTypes as $servingType)
                    <tr>
                        @if ($selectedCaterer == null)
                            <td>{{ $foodDetail->foodCategory->caterer->name }}</td>
                        @endif
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
            @if ($selectedCaterer == null)
                <th>Caterer</th>
            @endif
            <th>Name</th>
            <th>Desc</th>
            <th>Price</th>
        </thead>
        <tbody>
            @foreach ($utilities as $utility)
                <tr>
                    <td>{{ $utility->id }}</td>
                    @if ($selectedCaterer == null)
                        <td>{{ $utility->caterer->name }}</td>
                    @endif
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
