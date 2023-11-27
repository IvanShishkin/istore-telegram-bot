<?php

namespace Domain\Wallets;

use App\Domain\User\Models\User;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\IncreaseBalanceException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\StoreWallet;
use App\Domain\Wallets\UserWallet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    use DatabaseTransactions;

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

    public function testInit()
    {
        $storeWalletModel = $this->mockWallet();

        $wallet = new UserWallet($storeWalletModel->number);
        $wallet->init($storeWalletModel->number);

        $this->assertIsObject($wallet, StoreWallet::class);
    }

    public function testErrorInitialization()
    {
        $this->expectException(InitializationException::class);

        $uuid = Uuid::uuid4();
        $wallet = new UserWallet($uuid);
        $wallet->init($uuid);
    }

    public function testGetNumber()
    {
        $uuid = Uuid::uuid4();
        $wallet = new UserWallet($uuid);

        $this->assertEquals($wallet->getNumber(), $uuid);
    }

    public function testGetType()
    {
        $uuid = Uuid::uuid4();
        $wallet = new UserWallet($uuid);

        $this->assertEquals(WalletTypesEnum::USER, $wallet->getType());
    }

    public function testGetBalanceNewWallet()
    {
        $walletModel = $this->mockWallet();
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);

        $this->assertEquals($wallet->balance(), 0);
    }

    public function testGetBalance()
    {
        $walletModel = $this->mockWallet(['balance' => 1000]);
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);

        $this->assertEquals($wallet->balance(), 1000);
    }

    public function testIncrease()
    {
        $walletModel = $this->mockWallet();
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);
        $wallet->increase(100);

        $walletData = UserWalletModel::where(['number' => $walletNumber])->first();

        $this->assertEquals($walletData->balance, 100);
    }

    public function testFailedIncrease()
    {
        $walletModel = $this->mockWallet();
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);

        $this->expectException(IncreaseBalanceException::class);

        $wallet->increase(-100);
    }

    public function testReduce()
    {
        $walletModel = $this->mockWallet(['balance' => 120]);
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);
        $wallet->reduce(100);

        $walletData = UserWalletModel::where(['number' => $walletNumber])->first();

        $this->assertEquals($walletData->balance, 20);
    }

    public function testFailedReduce()
    {
        $walletModel = $this->mockWallet(['balance' => 120]);
        $walletNumber = $walletModel->number;

        $wallet = new UserWallet($walletNumber);

        $this->expectException(ReduceBalanceException::class);

        $wallet->reduce(400);
    }
}
