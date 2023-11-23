<?php
declare(strict_types=1);

namespace Domain\Wallets\Services;

use App\Domain\User\Exception\AlreadyExistsException;
use App\Domain\User\Models\User;
use App\Domain\User\Services\UserAuthService;
use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\Services\UserWalletService;
use App\Domain\Wallets\UserWallet;
use Database\Factories\UserWalletFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UserWalletServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserWalletService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(UserWalletService::class);
    }

    protected function mockWallet($additionalMockValues = []): UserWalletModel
    {
        $walletUuid = WalletUuid::factory()->create()->first();
        $user = User::factory()->create()->first();
        $defaultValues = [
            'number' => $walletUuid->uuid,
            'holder_id' => $user->id
        ];

        if (!empty($additionalMockValues)) {
            $defaultValues = array_merge($defaultValues, $additionalMockValues);
        }

        return UserWalletModel::factory()->create($defaultValues);
    }

    public function testGetWalletData()
    {
        $mockWallet = $this->mockWallet();

        $result = $this->service->getWalletData($mockWallet->holder_id);

        $this->assertIsObject($result, WalletDto::class);
        $this->assertEquals($result->holder_id, $mockWallet->holder_id);
        $this->assertEquals($result->number, $mockWallet->number);
        $this->assertEquals($result->balance, $mockWallet->balance);
    }

    public function testWalletNotExists()
    {
        $this->expectException(WalletNotExistsException::class);
        $result = $this->service->getWalletData(999999);
    }

    public function testInitWallet()
    {
        $mockWallet = $this->mockWallet();
        $result = $this->service->initWallet($mockWallet->holder_id);
        $this->assertIsObject($result, UserWallet::class);
    }

    public function testCreate()
    {
        $user = User::factory()->create()->first();

        $result = $this->service->create($user->id);

        $this->assertEquals($result->holder_id, $user->id);
        $this->assertEquals(0, $result->balance);
        $this->assertIsObject($result, WalletDto::class);
    }

    public function testCreateAlreadyExists()
    {
        $mock = $this->mockWallet();

        $this->expectException(WalletAlreadyExistsException::class);

        $this->service->create($mock->holder_id);
    }
}
