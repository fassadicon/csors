<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'caterer_id',
        'promo_id',
        'payment_id',
        'deducted_amount',
        'remarks'
    ];

    public function user() {}
    public function caterer() {}
    public function promo() {}
    public function payment() {}
}
