<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_id',
        'customer_id',
        'total_amount',
        'pax',
        'from',
        'to',
        'status',
        'remarks',
    ];

    public function service() : BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}
