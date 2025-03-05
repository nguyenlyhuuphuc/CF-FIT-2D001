<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'order_payment_method';

    //1 order payment method -> belongsTo order

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
