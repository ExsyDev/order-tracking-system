<?php

namespace App\Http\Controllers\API;

use App\Actions\Order\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Order\OrderRequest;
use App\Http\Requests\API\Order\OrderStatusRequest;
use App\Http\Resources\API\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Get orders list
     * @group Order
     * @bodyParam status string Status of the order. Example: pending
     * @responseFile status=200 responses/order/index.json
     */
    public function index(OrderStatusRequest $request)
    {
        $status = $request->validated()['status'] ?? null;

        $orders = Order::query()
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        return OrderResource::collection($orders);
    }

    /**
     * Create order
     * @group Order
     * @bodyParam order_number string required Order number. Example: 123456
     * @bodyParam total_amount number required Total amount of the order. Example: 100.00
     * @bodyParam items array required Items of the order. Example: [{"name": "item1", "price": 10.00}]
     * @responseFile status=201 responses/order/store.json
     */
    public function store(CreateOrderAction $orderAction, OrderRequest $request)
    {
        return new OrderResource($orderAction->handle($request->validated()));
    }

    /**
     * Show order
     * @group Order
     * @urlParam orderNumber string required Order number. Example: 123456
     * @responseFile status=200 responses/order/show.json
     */
    public function show(string $orderNumber)
    {
        $order = Order::whereOrderNumber($orderNumber)
            ->with('items')
            ->firstOrFail();

        return new OrderResource($order);
    }
}
