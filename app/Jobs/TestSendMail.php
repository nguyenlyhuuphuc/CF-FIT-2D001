<?php

namespace App\Jobs;

use App\Mail\OrderUserEmail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class TestSendMail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::find(8);

        Mail::to('nguyenlyhuuphucwork@gmail.com')->send(new OrderUserEmail($order));
    }
}
