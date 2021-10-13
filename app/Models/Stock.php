<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    function currentPrice() {
        $price = 0;
        $transaction = $this->hasMany(Price::class)->orderBy('created_at','desc')->first();
        if ($transaction) {
            $price = $transaction->price;
        }
        return $price;
    }
}
