<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // use HasFactory;

    protected $fillable = [

        /* ===== User ===== */
        'user_id',

        /* ===== Amounts ===== */
        'subtotal',
        'shipping',
        'coupon_code',
        'discount',
        'grand_total',

        /* ===== Payment ===== */
        'payment_method',
        'payment_status',

        /* ===== Address ===== */
        'first_name',
        'last_name',
        'email',
        'phone',
        'country_id',
        'address',
        'apartment',
        'city',
        'state',
        'zip',
        'notes',

        /* ===== Order Status ===== */
        'status',
    ];
}
