<?php

declare(strict_types=1);

namespace Domain\Store\Services;

use App\Domain\Store\Enums\OrderStatusEnum;
use App\Domain\Store\Exceptions\OrderChangeStatusException;
use App\Domain\Store\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Unit\Domain\Traits\OrderMockTrait;

class OrderStatusServiceTest extends TestCase
{
    use RefreshDatabase;
    use OrderMockTrait;

    protected OrderStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = \App::make(OrderStatusService::class);
    }

    public function testCancelNewOrder()
    {
        $result = $this->service->cancel($this->mockOrder()->id);

        $this->assertEquals(OrderStatusEnum::CANCEL, $result->getStatus());
    }

    public function testCancelProcessingOrder()
    {
        $result = $this->service->cancel($this->mockOrder(OrderStatusEnum::IN_PROCESSING)->id);

        $this->assertEquals(OrderStatusEnum::CANCEL, $result->getStatus());
    }

    public function testErrorCancel()
    {
        $this->expectException(OrderChangeStatusException::class);
        $this->service->cancel($this->mockOrder(OrderStatusEnum::CANCEL)->id);

        $this->expectException(OrderChangeStatusException::class);
        $this->service->cancel($this->mockOrder(OrderStatusEnum::PROCESSED)->id);
    }

    public function testInProcessing()
    {
        $result = $this->service->inProcessing($this->mockOrder()->id);

        $this->assertEquals(OrderStatusEnum::IN_PROCESSING, $result->getStatus());
    }

    public function testErrorInProcessing()
    {
        $this->expectException(OrderChangeStatusException::class);
        $this->service->inProcessing($this->mockOrder(OrderStatusEnum::CANCEL)->id);

        $this->expectException(OrderChangeStatusException::class);
        $this->service->inProcessing($this->mockOrder(OrderStatusEnum::PROCESSED)->id);
    }

    public function testProcessedNewOrder()
    {
        $result = $this->service->processed($this->mockOrder()->id);

        $this->assertEquals(OrderStatusEnum::PROCESSED, $result->getStatus());
    }

    public function testProcessedInProcessingOrder()
    {
        $result = $this->service->processed($this->mockOrder(OrderStatusEnum::IN_PROCESSING)->id);

        $this->assertEquals(OrderStatusEnum::PROCESSED, $result->getStatus());
    }

    public function testErrorProcessed()
    {
        $this->expectException(OrderChangeStatusException::class);
        $this->service->inProcessing($this->mockOrder(OrderStatusEnum::CANCEL)->id);

        $this->expectException(OrderChangeStatusException::class);
        $this->service->inProcessing($this->mockOrder(OrderStatusEnum::PROCESSED)->id);
    }
}
