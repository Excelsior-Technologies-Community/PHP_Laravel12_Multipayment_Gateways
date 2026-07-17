<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'gateway',
        'transaction_id',
        'amount',
        'status',
        'is_simulation',
    ];
}