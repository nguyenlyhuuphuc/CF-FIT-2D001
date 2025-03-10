<?php

namespace App\Listeners;

use App\Events\OrderSucceed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MinusQtyProduct
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderSucceed $event): void
    {
        //order = 6
        $order = $event->order;

        //order item = 3 items
        $orderItems = $order->orderItems;

        foreach ($orderItems as $orderItem) {
            $product = $orderItem->product;
            $product->qty -= $orderItem->qty;
            $product->save();
        }
    }
}
