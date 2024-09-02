<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\CancellationRequest;
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
            'status' => 0,
            'reason' => 'Please cancel my order.',
            'response' => null,
        ]);

        CancellationRequest::create([
            'order_id' => 2,
            'status' => 1,
            'reason' => 'I changed my mind.',
            'response' => 'Too late for cancellation.',
        ]);

        CancellationRequest::create([
            'order_id' => 3,
            'status' => 2,
            'reason' => 'Too slow to respond!',
            'response' => 'Refund ongoing. Sorry for the inconvenience.',
        ]);
    }
}
