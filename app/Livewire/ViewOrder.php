<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Ixudra\Curl\Facades\Curl;
use Masmerise\Toaster\Toaster;
use App\Models\CancellationRequest;

class ViewOrder extends Component
{
    public Order $order;
    public $data;
    public $auth_paymongo;
    public $cancellationRequestReason;
    public $cancellationRequestResponse;
    public $cancellationRequestStatus;
    public $canRequestCancellation = false;

    public function mount(Order $order)
    {
        $this->order = $order->load('caterer', 'orderItems', 'cancellationRequest');
        if ($this->order->cancellationRequest) {
            $this->cancellationRequestReason = $this->order->cancellationRequest->reason;
            $this->cancellationRequestResponse = $this->order->cancellationRequest->response;
            $this->cancellationRequestStatus = $this->order->cancellationRequest->status;
        } else {
            if (
                $this->order->order_status->value === 'pending'
            ) {
                $this->canRequestCancellation = true;
            } else if (
                $this->order->order_status->value === 'confirmed'
            ) {
                if (
                    $this->order->payment_status->value === 'pending' ||
                    $this->order->payment_status->value === 'partial'
                ) {
                    $this->canRequestCancellation = true;
                }
            }
        }
        $this->auth_paymongo = base64_encode(env('PAYMONGO_SECRET_KEY'));
        $this->data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'quantity' => 1,
                        ]
                    ],
                    'payment_method_types' => [
                        'card',
                        'gcash',
                        'grab_pay',
                        'paymaya',
                        'brankas_landbank',
                        'brankas_metrobank',
                        'billease',
                        'dob',
                        'dob_ubp',
                        'qrph',
                    ],
                    'description' => 'test',
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                    'cancel_url' => url("payment-cancelled"),
                ],
            ],
        ];
    }

    public function payPartial()
    {
        $downPayment = $this->order->total_amount * 0.7;
        $downPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($downPayment, 2)))));

        $this->data['data']['attributes']['success_url'] = route("partial-payment-existing-success");
        $this->data['data']['attributes']['line_items'][0]['amount'] = $downPayment;
        $this->data['data']['attributes']['line_items'][0]['name'] = 'Downpayment';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
            ->asJson()
            ->post();

        session()->put('pay_partial', [
            'order_id' => $this->order->id,
            'checkout_id' => $response->data->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function payFull()
    {
        $fullPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($this->order->total_amount, 2)))));

        $this->data['data']['attributes']['success_url'] = route("full-payment-existing-success");
        $this->data['data']['attributes']['line_items'][0]['amount'] = $fullPayment;
        $this->data['data']['attributes']['line_items'][0]['name'] = 'Payment 1 of 1';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
            ->asJson()
            ->post();

        session()->put('pay_full', [
            'order_id' => $this->order->id,
            'checkout_id' => $response->data->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function payRemaining()
    {
        $remainingPayment = $this->order->total_amount * 0.3;
        $remainingPayment = intval(str_replace(".", "", trim(preg_replace("/[^-0-9\.]/", "", number_format($remainingPayment, 2)))));

        $this->data['data']['attributes']['success_url'] = route('remaining-payment-success');
        $this->data['data']['attributes']['line_items'][0]['amount'] = $remainingPayment;
        $this->data['data']['attributes']['line_items'][0]['name'] = 'Payment 2 of 2';

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('accept: application/json')
            ->withHeader('Authorization: Basic ' . $this->auth_paymongo)
            ->withData($this->data)
            ->asJson()
            ->post();

        session()->put('pay_remaining', [
            'order_id' => $this->order->id,
            'checkout_id' => $response->data->id,
        ]);

        return redirect($response->data->attributes->checkout_url);
    }

    public function updateCancellationRequest()
    {
        CancellationRequest::where('order_id', $this->order->id)->update([
            'reason' => $this->reason,
        ]);

        redirect('order-history')->with('warning', 'Cancellation request updated. Please wait to for the response of the caterer. Thank you!');
    }

    public function removeCancellationRequest()
    {
        CancellationRequest::where('order_id', $this->order->id)->delete();

        redirect('order-history')->with('warning', 'Cancellation request deleted. Thank you!');
    }

    public function cancel()
    {
        return redirect()->route('request-cancellation.create', ['order' => $this->order]);
    }

    public function render()
    {
        return view('livewire.view-order');
    }
}
