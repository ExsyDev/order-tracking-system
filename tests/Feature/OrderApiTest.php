<?php

namespace Feature;

use App\Enums\Order\OrderStatus;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    /**
     * Order number for testing.
     */
    const ORDER_NUMBER = '12345';

    public function test_order_can_be_created()
    {
        $testOrder = $this->createOrder(self::ORDER_NUMBER);

        $testOrder->assertStatus(201);

        $this->assertDatabaseHas('orders', ['order_number' => self::ORDER_NUMBER]);
    }

    public function test_order_can_be_shown()
    {
        $this->createOrder(self::ORDER_NUMBER);

        $response = $this->getJson("/api/orders/" . self::ORDER_NUMBER);

        $response->assertStatus(200)
            ->assertJsonFragment(['order_number' => self::ORDER_NUMBER, 'status' => OrderStatus::PENDING]);
    }

    public function test_orders_can_be_listed()
    {
        $this->createOrder(self::ORDER_NUMBER);

        $response = $this->getJson("/api/orders?status=" . OrderStatus::PENDING->value);

        $response->assertStatus(200)
            ->assertJsonFragment(['order_number' => self::ORDER_NUMBER, 'status' => OrderStatus::PENDING]);
    }

    /**
     * @param string $orderNumber
     * @return TestResponse
     */
    protected function createOrder(string $orderNumber): TestResponse
    {
        return $this->postJson('/api/orders', [
            'order_number' => $orderNumber,
            'total_amount' => 100.50,
            'items' => [
                ['product_name' => 'Product A', 'quantity' => 1, 'price' => 50.25],
                ['product_name' => 'Product B', 'quantity' => 2, 'price' => 25.12],
            ],
        ]);
    }
}
