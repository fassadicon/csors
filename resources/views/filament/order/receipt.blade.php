<script src="https://cdn.tailwindcss.com"></script>
<div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
    <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Receipt Details</h2>
    <table class="w-full mb-6 text-left border-collapse table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Item</th>
                <th class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Price</th>
            </tr>
        </thead>
        <tbody>
            @php
            use Illuminate\Support\Str;
            $total = 0; // Initialize total variable
            @endphp
            @foreach ($order as $item)
            <tr class="transition duration-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">
                    @php
                    $itemName = '';
                    if ($item->orderable) {
                    if (get_class($item->orderable) === 'App\Models\Food' && $item->orderable->foodDetail) {
                    $itemName = $item->orderable->foodDetail->name . ' - ' . $item->orderable->servingType->name . ' ('
                    . $item->quantity . ' pax)';
                    } elseif ($item->orderable) {
                    $itemName = $item->orderable->name . ' (' . $item->quantity . ' set/pcs)';
                    }
                    }
                    @endphp
                    {{ $itemName ?: 'Item not available' }}
                </td>
                <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">
                    ₱{{ number_format($item->amount, 2) }}
                </td>
                @php
                $total += $item->amount; // Add item price to total
                @endphp
            </tr>
            @endforeach
            <tr class="font-bold bg-gray-200 dark:bg-gray-700">
                <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Total</td>
                <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">₱{{ number_format($total, 2) }}</td>
                <!-- Display the total -->
            </tr>
        </tbody>
    </table>
    <hr class="my-4 border-gray-300 dark:border-gray-600">
    <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Payments Made</h3>
    <table class="w-full text-left border-collapse table-auto">
        <thead>
            <tr class="bg-gray-200 dark:bg-gray-700">
                <th class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Payment Method</th>
                <th class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Amount</th>
                <th class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Date</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalPayments = 0; // Initialize total payments variable
            @endphp
            @if (count($payments) <= 0) <td class="self-center w-full p-2 text-gray-800 dark:text-gray-200">No payments
                yet.</td>
                @endif
                @foreach ($payments as $payment)
                <tr class="transition duration-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                    <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">
                        {{ Str::camel($payment->method) ?: Str::camel($payment->type) }}
                    </td>
                    <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">
                        ₱{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">
                        {{ $payment->created_at->format('Y-m-d H:i') }}
                    </td>
                    @php
                    $totalPayments += $payment->amount; // Add payment amount to total payments
                    @endphp
                </tr>
                @endforeach
                <tr class="font-bold bg-gray-200 dark:bg-gray-700">
                    <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">Total Payments</td>
                    <td class="px-4 py-2 text-gray-800 border-b dark:text-gray-200">₱{{ number_format($totalPayments, 2)
                        }}</td>
                    <td class="px-4 py-2 border-b"></td> <!-- Empty cell for date -->
                </tr>
        </tbody>
    </table>
    @php
    $balance = $total - $totalPayments; // Calculate balance
    @endphp
    @if ($balance > 0)
    <div class="p-4 mt-4 !text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-800 dark:text-red-200">
        <strong>Insufficient Payment!</strong> You still owe ₱{{ number_format($balance, 2) }}.
    </div>
    @else
    <div
        class="p-4 mt-4 text-green-700 bg-green-100 border border-green-400 rounded dark:bg-green-800 dark:text-green-200">
        <strong>Payment Complete!</strong> You have paid in full.
    </div>
    @endif
</div>