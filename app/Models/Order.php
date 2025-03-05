<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

//     1 order -> N order item
// 1 order -> belongTo User
// 1 order -> N payment method

    public function orderItems(){
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function orderPaymentMethods(){
        return $this->hasMany(OrderPaymentMethod::class, 'order_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
