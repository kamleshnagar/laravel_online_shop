<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'country_id',
        'shipping_charge'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
