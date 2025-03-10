<?php

namespace App\Listeners;

use App\Events\OrderSucceed;
use App\Jobs\TestSendMail;
use App\Mail\OrderAdminEmail;
use App\Mail\OrderUserEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToAdmin
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
        // $order = $event->order;

        // Mail::to(env('ADMIN_EMAIL'))->send(new OrderAdminEmail($order));

        // $order = $event->order;
        // $user = $order->user;

        // Mail::to($user->email)->send(new OrderUserEmail($order));

        TestSendMail::dispatch();
    }
}
