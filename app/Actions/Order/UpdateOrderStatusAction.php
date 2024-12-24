<?php

namespace App\Actions\Order;

use App\Actions\Core\CoreAction;
use App\Enums\Order\OrderStatus;
use App\Events\Order\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class UpdateOrderStatusAction extends CoreAction
{
    /**
     * Update the order status
     * @param Order $order
     * @param string $externalApiUrl
     * @return void
     */
    public function handle(Order $order, string $externalApiUrl): void
    {
        $response = Http::get($externalApiUrl, [
            'order_number' => $order->order_number,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['status']) && OrderStatus::isValid($data['status'])) {
                $order->update(['status' => $data['status']]);

                event(new OrderStatusUpdated($order));
            }
        }
    }
}
