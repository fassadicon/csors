@php
use Illuminate\Support\Str;
@endphp
<div x-data="{ showPopup: false }" class="p-4 rounded-md bg-jt-white">

    @if ($order->order_status->value == 'declined')
        <div class="w-[90%] md:w-[70%] mx-auto pt-5 pb-2 -mt-10 text-white bg-red-700 h-fit">
            <center>
                <h3>Order Declined</h3>
                <p>reason</p>
                <hr class="mx-4 my-2">
                <p>{{$order->decline_reason}}</p>
            </center>
        </div>
    @endif
    <div class="flex flex-col justify-between px-4 md:flex-row">
        <x-mary-header title="Order #{{ $order->id }}" {{--
            subtitle="# {{ $order->id }} - PHP {{ $order->total_amount }}" --}} class="!my-4 ">
        </x-mary-header>

        <div class="flex flex-col justify-end mt-4 gap-y-4">
            <div class="flex-row flex-wrap justify-end md:flex gap-x-2">
                <x-mary-badge value="Payment: {{ $order->payment_status->getLabel() }}"
                    class="badge-{{ $order->payment_status->getMaryColor() }}" />
                <x-mary-badge value="Order: {{ $order->order_status->getLabel() }}"
                    class="badge-{{ $order->order_status->getMaryColor() }}" />
            </div>
            {{-- <h3>#{{ $order->id }} - Total Php {{ number_format($order->final_amount, 2) }}</h3> --}}
        </div>
    </div>
    <hr class="block my-4 md:my-0 md:hidden">
    <div class="flex flex-col justify-around gap-y-4 md:gap-y-0 md:flex-row gap-x-4">
        <div class="w-[100%] md:w-[50%] max-h-[400px] overflow-y-auto">
            @foreach ($order->orderItems as $orderItem)
            <div>
                <x-mary-list-item :item="$orderItem" no-separator no-hover>
                    <x-slot:avatar>
                        <x-mary-avatar :image="asset('images/placeholder.jpg')" />
                    </x-slot:avatar>
                    <x-slot:value>
                        @if ($orderItem->orderable_type == 'App\Models\Food')
                        {{ $orderItem->orderable->foodDetail->name }} -
                        {{ $orderItem->orderable->servingType->name }}
                        @else
                        {{ $orderItem->orderable->name }}
                        @endif
                    </x-slot:value>
                    <x-slot:sub-value>
                        @if ($orderItem->orderable_type == 'App\Models\Food')
                        Pax: {{ $orderItem->quantity }}
                        @else
                        Package/Pieces/Pax: {{ $orderItem->quantity }}
                        @endif
                    </x-slot:sub-value>
                    <x-slot:actions>
                        PHP {{ number_format($orderItem->amount, 2) }}
                    </x-slot:actions>
                </x-mary-list-item>
            </div>
            @endforeach
        </div>
        @if ($payments)
        <div class="flex flex-col p-4 shadow-md w-[100%] md:w-[50%]">
            <x-mary-header title="Payments" class="!my-4 ">
            </x-mary-header>
            <x-mary-table :headers="$headers" :rows="$payments" striped show-empty-text
                class="border border-collapse" />
        </div>
        @endif
    </div>
    {{--
    <x-mary-header title="Total: {{ $totalAmount }}" class="!my-2" separator /> --}}
    <hr class="mx-4 my-4">
    <div class="">
        <div class="flex flex-col items-start justify-between w-full p-4 md:flex-row gap-y-4">
            <div class="space-y-2">
                <x-mary-header title="Customer Information" class="!my-2" subtitle="Customer: {{ $order->user->name }}"
                    separator />
                <p class="text-gray-900 dark:text-gray-100">

                <p class="text-gray-900 dark:text-gray-100">
                    Caterer: {{ $order->caterer->name }}
                </p>
                <p class="text-gray-900 dark:text-gray-100">
                    from {{ $order->start->format('M j, Y - g:ia') }} to {{ $order->end->format('M j, Y - g:ia') }}
                    {{-- - {{ $order->start->diffForHumans() --}}
                </p>
                <p class="text-gray-900 dark:text-gray-100">
                    Location: {{ $order->location }}
                </p>
                <p class="text-gray-900 dark:text-gray-100">
                    Remarks: {{ $order->remarks }}
                </p>
                <p class="text-gray-900 dark:text-gray-100">
                    Date Ordered: {{ $order->created_at->format('M j, Y - g:ia') }}
                </p>
            </div>
            <hr class="block my-4 md:my-0 md:hidden">
            <div class="flex flex-col gap-y-2 p-4 md:p-0 w-[90%] md:w-[45%] ">
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
                    <div class="flex gap-x-2 w-fit">
                        <p>Promo:</p>
                        <div class="flex items-center mx-auto rounded-full w-max bg-jt-primary">
                            <span
                                class="flex items-center justify-center px-2 text-base font-semibold text-gray-900 bg-yellow-300 rounded-full">{{ $order->promo->name }}</span>
                            <p class="mx-4 text-white uppercase">Php {{ number_format($order->deducted_amount, 2) }}</p>
                        </div>
                        {{-- <div>Php {{ number_format($order->deducted_amount, 2) }}</div> --}}
                    </div>
                @endif
                
                @if ($order->promo)
                    <p class="text-sm italic">Subtotal: Php {{ number_format($order->total_amount+ $order->deducted_amount , 2) }} - <b class="text-jt-primary">Php {{ number_format($order->deducted_amount, 2) }}</b></p>
                    {{-- <p class="text-sm italic">New Subtotal: Php {{ number_format($subtotalMinusPromo, 2) }}</p> --}}
                {{-- @else
                    <p class="text-sm italic">New Subtotal: Php {{ number_format($subtotalMinusPromo, 2) }}</p> --}}
                @endif

                <p class="text-sm italic">Tax: Php {{ number_format($subtotalMinusPromo * 0.12, 2) }}</p>
                <p class="text-sm italic">Delivery Fee: Php {{ number_format($order->delivery_amount, 2) }}</p>
                <h4>Total Amount: Php {{ number_format($subtotalMinusPromo + $order->delivery_amount, 2) }}</h4>
                @if ($order->payment_status->value == 'partial')
                @php
                    $remainingPayment = ($this->order->total_amount - $this->order->payments->first()->amount + $this->order->delivery_amount);
                    // dd($remainingPayment);
                @endphp
                    <h4>Balance: Php {{ number_format($remainingPayment, 2) }}</h4>
                @endif

                {{-- + ($subtotalMinusPromo * 0.12)  --}}
                <hr class="mx-4 my-4">
                @if ($order->cancellationRequest)
                <x-mary-header title="Cancellation Request" class="!my-2" separator />
                <div class="space-y-2">
                    <p>Status: {{ $cancellationRequestStatus }}</p>
                    @if ($cancellationRequestStatus->value == 'approved' || $cancellationRequestStatus->value ==
                    'declined')
                    <x-mary-textarea label="Response of Caterer" wire:model="cancellationRequestReason" placeholder="Notes for the Caterer"
                    rows="4" inline />
                    <p>Caterer Response: {{ $cancellationRequestResponse }}</p>
                    {{-- <x-mary-textarea label="Response of Caterer" wire:model="cancellationRequestResponse"
                        placeholder="Awaiting Reply from the Caterer..." rows="4" inline readonly /> --}}
                    @else
                    <p>Reason: {{ $cancellationRequestReason }}</p>
                    <x-mary-textarea label="Cancellation Reason" wire:model="reason"
                        placeholder="Cancellation Reason..." rows="4" inline />
                    <x-mary-textarea label="Response of Caterer" wire:model="cancellationRequestResponse"
                        placeholder="Awaiting Reply from the Caterer..." rows="4" inline readonly />

                    @endif
                </div>
                <hr class="my-4">
                @if ($cancellationRequestStatus->value == 'pending')
                <x-mary-button wire:click='updateCancellationRequest' label="Update Cancellation Request"
                    class="btn-primary" spinner />
                <x-mary-button wire:click='removeCancellationRequest' label="Cancel Cancellation Request"
                    class="btn-warning" spinner />
                @endif
                @endif

                @if ($canPay)
                <div class="flex gap-x-2">
                    @if ($order->payment_status->value == 'pending')
                    <x-primary-button class="w-full bg-slate-800 flex !justify-center !text-center"
                        wire:click='payPartial'>{{ __('Pay DP (') . ($order->caterer->downpayment) . '%) - ₱' .
                        number_format(($order->total_amount  + $order->delivery_amount) * ($order->caterer->downpayment / 100), 2)  }}</x-primary-button>
                    <x-primary-button class="w-full btn-primary flex !justify-center" wire:click='payFull'>{{ __('Pay
                        Full - ₱') . number_format(($order->total_amount + $order->delivery_amount), 2)}}
                    </x-primary-button>
                    @elseif ($order->payment_status->value == 'partial')
                    <x-primary-button class="w-full btn-primary flex !justify-center" wire:click='payRemaining'>{{
                        __('Pay
                        Remaining Balance') }}</x-primary-button>
                    @endif
                </div>
                @endif

                {{-- CANCEL --}}
                @php
                    $firstPayment = $order->payments->first()?->created_at;
                    // Check if the first payment is within ±24 hours of the current date
                    $isWithin24Hours = $firstPayment && abs(now()->diffInHours($firstPayment, false)) <= 24;
                    // dd($order->order_status->name);

                @endphp
                @unless ($order->cancellationRequest)
                    @if (($canRequestCancellation && $isWithin24Hours) || $order->order_status->name === 'Pending')
                        <x-danger-button class="flex justify-center bg-red-700" wire:click='cancel'>
                            {{ __('Request to Cancel') }}
                        </x-danger-button>
                    @endif
                @endunless
                
                {{-- TEMPORARY --}}
                @if ($canPay && $order->payment_status->value !== 'paid' && $order->payment_status->value !== 'refunded')
                <x-primary-button class="w-full btn-primary !bg-blue-500  flex !justify-center" @click='showPopup=true'>
                    ALTERNATIVE PAYMENT (GCASH)
                </x-primary-button>
                @endif
                <a href="{{ route('order-history') }}">
                    <x-mary-button label="Back to Order History" class="w-full py-2 btn-outline" />
                </a>
            </div>
        </div>
    </div>

    <template x-if="showPopup">
        <div class="fixed top-0 left-0 z-50 flex items-center justify-center w-full h-screen bg-black/50">
            <div class="flex flex-col justify-center items-center w-[300px] p-4 h-[450px] bg-white rounded-xl">
                {{-- @dd($order->user && Str::contains($order->user->name, 'Omsim', true)) --}}
                @if ($order->caterer->qr_path)
                    <img src="{{ asset('storage/'.$order->caterer->qr_path) }}" alt="Pauline GCash QR"
                    class="w-[250px] h-[350px] object-cover object-center">
                @else
                    <p class="mx-4 text-center">No alternative payment options have been set up yet.</p>
                @endif

                <button @click="showPopup=false" class="w-full p-4 mt-2 text-white bg-slate-900">CLOSE</button>
            </div>
        </div>
    </template>


</div>