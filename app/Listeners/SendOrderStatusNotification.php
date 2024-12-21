<?php

namespace App\Listeners;

use App\Events\Order\OrderStatusUpdated;

class SendOrderStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(OrderStatusUpdated $event) {}

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //$event->order->user->notify(new SendOrderStatusNotification($event->order));
    }
}
