<?php

namespace App\Jobs\Order;

use App\Enums\Order\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use \App\Actions\Order\UpdateOrderStatusAction as UpdateOrderStatusAction;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $externalApiUrl) {}

    /**
     * Execute the job.
     */
    public function handle(UpdateOrderStatusAction $orderAction): void
    {
        try {
            Order::whereIn('status', [OrderStatus::PENDING, OrderStatus::SHIPPED])
                ->chunk(100, function ($orders) use ($orderAction) {
                    foreach ($orders as $order) {
                        $orderAction->handle($order, $this->externalApiUrl);
                    }
                });
        } catch (\Exception $e) {
            Log::error('Error in UpdateOrderStatusAction job: ' . $e->getMessage());
        }
    }
}
