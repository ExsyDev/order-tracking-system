<?php

namespace App\Actions\Order;

use App\Actions\Core\CoreAction;
use App\Http\Requests\API\Order\OrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CreateOrderAction extends CoreAction
{
    /**
     * Store a newly created order
     * @param OrderRequest $request
     * @return mixed
     */
    public function handle(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'order_number' => $data['order_number'],
                'total_amount' => $data['total_amount'],
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return $order->load('items');
        });
    }
}
