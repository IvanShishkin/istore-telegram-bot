<?php
declare(strict_types=1);

namespace Domain\Wallets\Services;

use App\Domain\User\Models\User;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\Services\ChangeBalanceService;
use App\Domain\Wallets\Services\StoreWalletService;
use App\Domain\Wallets\UserWallet;
use Faker\Core\Uuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeBalanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ChangeBalanceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ChangeBalanceService::class);
    }

    protected function mockUserWallet(int $balance = 0): UserWalletModel
    {
        $user = User::factory()->create()->first();

        $walletUuid = WalletUuid::factory()->create()->first();

        return UserWalletModel::factory()->create([
            'number' => $walletUuid->uuid,
            'holder_id' => $user->id,
            'balance' => $balance
        ])->first();
    }

    protected function getWalletModel(string $number): ?UserWalletModel
    {
        return UserWalletModel::where(['number' => $number])->first();
    }

    public function testIncrease()
    {
        $increaseValue = 100;
        $mock = $this->mockUserWallet();

        $wallet = new UserWallet($mock->number);
        $this->service->increase($wallet, $increaseValue);

        $model = $this->getWalletModel($mock->number);

        $this->assertEquals($model->balance, $increaseValue);

    }

    public function testIncreaseWalletNotExists()
    {
        $wallet = new UserWallet(\Ramsey\Uuid\Uuid::uuid4()->toString());

        $this->expectException(InitializationException::class);

        $this->service->increase($wallet, 100);
    }

    public function testReduce()
    {
        $reduceValue = 100;
        $mock = $this->mockUserWallet(200);

        $wallet = new UserWallet($mock->number);
        $this->service->reduce($wallet, $reduceValue);

        $model = $this->getWalletModel($mock->number);

        $this->assertEquals(100, $model->balance);
    }

    public function testReduceException()
    {
        $reduceValue = 100;
        $mock = $this->mockUserWallet(50);

        $wallet = new UserWallet($mock->number);

        $this->expectException(ReduceBalanceException::class);

        $this->service->reduce($wallet, $reduceValue);
    }

    public function testReduceWalletNotExists()
    {
        $wallet = new UserWallet(\Ramsey\Uuid\Uuid::uuid4()->toString());

        $this->expectException(InitializationException::class);

        $this->service->reduce($wallet, 100);
    }
}
