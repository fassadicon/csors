<?php

namespace App\Livewire;

use App\Mail\ReservationOrder;
use Carbon\Carbon;
use App\Models\Order as OrderModel;
use App\Models\Caterer;
use Livewire\Component;
use App\Models\OrderItem;
use Ixudra\Curl\Facades\Curl;
use Livewire\Attributes\Validate;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Order extends Component
{

    public $cart = [];
    public $caterer;

    // #[Validate('required|date|before:endDateTime')]
    public $startDateTime;

    // #[Validate('required|date|after:startDateTime')]
    public $endDateTime;

    public $location;
    public $remarks;
    public $promo;
    public $paymentType = 'full';

    public float $totalAmount;
    public float $downPaymentAmount;
    public float $originalTotalAmount;
    public float $deductedAmount;

    public $promos;

    public $recipient;

    public $disabledDates;

    public function mount()
    {
        $this->caterer = Caterer::find(session()->get('caterer'));

        $this->disabledDates = $this->caterer->disabledDates
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        $this->promos = $this->caterer->promos()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        $this->cart = session()->get('cart') ?? [];

        $this->originalTotalAmount = collect($this->cart)->flatMap(function ($orderItems) {
            return $orderItems;
        })->sum('price');

        $this->totalAmount = $this->originalTotalAmount;

        $this->updatedPromo();

        $this->recipient = auth()->user() ? auth()->user()->full_name : null;
    }

    // public function updatedEndDateTime()
    // {
    //     $this->validateOnly('startDateTime');
    // }

    // public function updatedStartDateTime()
    // {
    //     $this->validateOnly('endDateTime');
    // }

    public function submitOrder()
    {
        // $this->startDateTime = Carbon::parse($this->startDateTime);
        // $this->endDateTime = Carbon::parse($this->endDateTime);
        $this->validate([
            'startDateTime' => 'required|date|before:endDateTime',
            'endDateTime' => 'required|date|after:startDateTime'
        ]);

        if (auth()->guest()) {
            return redirect()->route('login');
        }

        DB::beginTransaction();

        try {
            // Prepare order items
            $orderItems = [];
            foreach ($this->cart as $categoryItems) {
                foreach ($categoryItems as $item) {
                    $orderItem = new OrderItem();
                    $orderItem->orderable_type = get_class($item['orderItem']);
                    $orderItem->orderable_id = $item['orderItem']->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->amount = $item['price'];
                    array_push($orderItems, $orderItem);
                }
            }

            // Save the order
            $order = OrderModel::create([
                'user_id' => auth()->id(),
                'recipient' => $this->recipient,
                'caterer_id' => session()->get('caterer'),
                'promo_id' => $this->promo,
                'deducted_amount' => $this->deductedAmount,
                'location' => $this->location,
                'remarks' => $this->remarks,
                'start' => $this->startDateTime,
                'end' => $this->endDateTime,
                'total_amount' => $this->totalAmount,
                'final_amount' => $this->totalAmount + ($this->totalAmount * .12),
            ]);

            // Save each order item
            foreach ($orderItems as $orderItem) {
                $orderItem->order_id = $order->id;
                $orderItem->save();
            }

            // Commit the transaction
            DB::commit();

            // Prepare the email recipient
            $recipient = User::whereHas('caterer', function ($query) {
                $query->where('id', session()->get('caterer'));
            })->first();

            // Send notification to recipient and the user
            $notificationToRecipient = 'New order #' . $order->id . ' from ' . auth()->user()->full_name;
            Notification::make()
                ->title($notificationToRecipient)
                ->sendToDatabase($recipient);

            $notificationToUser = 'New order #' . $order->id . ' has been submitted';
            Notification::make()
                ->title($notificationToUser)
                ->sendToDatabase(auth()->user());

            // Send the email to the user
            Mail::to(auth()->user()->email)->send(new ReservationOrder($order->id, $this->caterer, now()->format('Y-m-d H:i:s'), $this->startDateTime, $this->endDateTime));

            // Clear cart session data
            session()->forget('cart');

            // Redirect to the order view page with success message
            return redirect()->route('view-order', ['order' => $order->id])->with('success', 'Order has been successfully placed.');
        } catch (\Exception $e) {
            // Rollback the transaction if there’s an error
            DB::rollBack();

            // Log the exception message for debugging
            // \Log::error('Order Submission Error: ' . $e->getMessage());

            // Optionally log additional details
            // \Log::error('File: ' . $e->getFile() . ' | Line: ' . $e->getLine());

            // Return an error message to the user
            return redirect()->back()->with('error', 'An error occurred while placing the order. Please try again.');
        }
    }

    public function updatedPromo()
    {
        if ($this->promo != '') {
            $promo = $this->promos->find($this->promo);
            if ($promo->type == 'fixed') {
                $this->deductedAmount = $promo->value;
                $this->totalAmount = $this->originalTotalAmount - $this->deductedAmount;
                $this->downPaymentAmount = $this->totalAmount * 0.7;
            } else {
                $this->deductedAmount = ($this->originalTotalAmount * (floatval($promo->value) / 100));
                $this->totalAmount = $this->originalTotalAmount - ($this->originalTotalAmount * (floatval($promo->value) / 100));
                $this->downPaymentAmount = $this->totalAmount * 0.7;
            }
        } else {
            $this->totalAmount = collect($this->cart)->flatMap(function ($orderItems) {
                return $orderItems;
            })->sum('price');
            $this->downPaymentAmount = $this->totalAmount * 0.7;
            $this->deductedAmount = 0.0;
        }
    }

    public function render()
    {
        return view('livewire.order');
    }
}
