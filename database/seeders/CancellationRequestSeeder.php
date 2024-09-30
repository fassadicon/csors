<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\CancellationRequest;
use App\Enums\CancellationRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CancellationRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CancellationRequest::create([
            'order_id' => 1,
            'status' => CancellationRequestStatus::Pending,
            'reason' => 'Please cancel my order.',
            'response' => null,
        ]);

        CancellationRequest::create([
            'order_id' => 2,
            'status' => CancellationRequestStatus::Declined,
            'reason' => 'I changed my mind.',
            'response' => 'Too late for cancellation.',
        ]);

        CancellationRequest::create([
            'order_id' => 21,
            'status' => CancellationRequestStatus::Approved,
            'reason' => 'Too slow to respond!',
            'response' => 'Refund ongoing. Sorry for the inconvenience.',
        ]);
    }
}
