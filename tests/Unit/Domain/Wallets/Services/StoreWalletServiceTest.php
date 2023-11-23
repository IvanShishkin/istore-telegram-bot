<?php
declare(strict_types=1);

namespace Domain\Wallets\Services;

use App\Domain\User\Models\User;
use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\StoreBalanceModel;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\Services\StoreWalletService;
use App\Domain\Wallets\Services\UserWalletService;
use App\Domain\Wallets\StoreWallet;
use App\Domain\Wallets\UserWallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class StoreWalletServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StoreWalletService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = \App::make(StoreWalletService::class);
    }

    protected function mockWallet($additionalMockValues = []): StoreBalanceModel
    {
        $walletUuid = WalletUuid::factory()->create()->first();

        $defaultValues = [
            'number' => $walletUuid->uuid,
        ];

        if (!empty($additionalMockValues)) {
            $defaultValues = array_merge($defaultValues, $additionalMockValues);
        }

        return StoreBalanceModel::factory()->create($defaultValues);
    }

    public function testGetWalletData()
    {
        $mockWallet = $this->mockWallet();

        $result = $this->service->getWalletData();

        $this->assertIsObject($result, WalletDto::class);

        $this->assertEquals($result->number, $mockWallet->number);
        $this->assertEquals($result->balance, $mockWallet->balance);
    }

    public function testWalletNotExists()
    {
        $this->expectException(WalletNotExistsException::class);

        $this->service->getWalletData();
    }

    public function testInitWallet()
    {
        $this->mockWallet();

        $result = $this->service->initWallet();

        $this->assertIsObject($result, StoreWallet::class);
    }

    public function testCreate()
    {
        $result = $this->service->create();

        $this->assertEquals(0, $result->balance);
        $this->assertIsObject($result, WalletDto::class);
    }

    public function testCreateAlreadyExists()
    {
        $this->mockWallet();

        $this->expectException(WalletAlreadyExistsException::class);

        $this->service->create();
    }
}
