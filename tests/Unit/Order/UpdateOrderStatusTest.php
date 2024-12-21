<?php

namespace Tests\Unit\Order;

use App\Enums\Order\OrderStatus;
use App\Jobs\Order\UpdateOrderStatus;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateOrderStatusTest extends TestCase
{
    use RefreshDatabase;

    protected const EXTERNAL_API_URL = 'https://external-api.com/orders';

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->order = Order::create([
            'order_number' => '12345',
            'total_amount' => 200.50,
            'status' => OrderStatus::PENDING,
        ]);
    }

    /** @test */
    public function it_uses_action_to_update_order_status()
    {
        $mockAction = $this->mockAction($this->order, OrderStatus::SHIPPED->value);

        $job = new UpdateOrderStatus(self::EXTERNAL_API_URL);
        $job->handle($mockAction);

        $this->order->refresh();

        $this->assertEquals(OrderStatus::SHIPPED, $this->order->status);
    }

    /** @test */
    public function it_correctly_updates_order_status_via_action()
    {
        $this->fakeApiResponse(OrderStatus::SHIPPED->value);

        $action = new \App\Actions\Order\UpdateOrderStatus();
        $action->handle($this->order, self::EXTERNAL_API_URL);

        $this->order->refresh();

        $this->assertEquals(OrderStatus::SHIPPED, $this->order->status);
    }

    /** @test */
    public function it_handles_api_errors_gracefully()
    {
        $this->fakeApiResponse(null, 500);

        Queue::fake();

        UpdateOrderStatus::dispatch(self::EXTERNAL_API_URL);

        Queue::assertPushed(UpdateOrderStatus::class);

        $this->artisan('queue:work', ['--once' => true]);

        $this->order->refresh();
        $this->assertEquals(OrderStatus::PENDING, $this->order->status);
    }

    /**
     * @param Order $order
     * @param string $expectedStatus
     * @return MockInterface
     */
    protected function mockAction(Order $order, string $expectedStatus): MockInterface
    {
        return $this->partialMock(\App\Actions\Order\UpdateOrderStatus::class, function ($mock) use ($order, $expectedStatus) {
            $mock->shouldReceive('handle')
                ->once()
                ->withArgs(function ($mockOrder, $url) use ($order) {
                    return $mockOrder->order_number === $order->order_number
                        && $url === self::EXTERNAL_API_URL;
                })
                ->andReturnUsing(function ($mockOrder) use ($expectedStatus) {
                    $mockOrder->update(['status' => $expectedStatus]);
                });
        });
    }

    /**
     * @param string|null $status
     * @param int $statusCode
     * @return void
     */
    protected function fakeApiResponse(?string $status, int $statusCode = 200): void
    {
        Http::fake([
            self::EXTERNAL_API_URL . '*' => Http::response($status ? ['status' => $status] : [], $statusCode),
        ]);
    }
}
