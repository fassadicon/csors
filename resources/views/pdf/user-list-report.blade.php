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

@if ($caterers != null)
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
                        <td>{{ $caterer->user->full_name }} {{ $caterer->user->phone_number }}
                            {{ $caterer->user->email }}
                        </td>
                        <td>{{ $caterer->name }}</td>
                        <td>{{ $caterer->phone_number }}</td>
                        <td>{{ $caterer->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($caterer->updated_at)->format('M j, Y g:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@if ($customers != null)
    <div>
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
@endif

<br>
Generated on: {{ now() }}
<br>
Generated on: {{ auth()->user()->name }}

<body>

</body>

</html>
