<?php

namespace Domain\Wallets;

use App\Domain\User\Services\UserAuthService;
use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\StoreWallet;
use App\Domain\Wallets\UserWallet;
use App\Domain\Wallets\WalletFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class WalletFactoryTest extends TestCase
{
    protected WalletFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = app(WalletFactory::class);
    }

    public function testMakeStoreWallet()
    {
        $wallet = $this->factory->make(Uuid::uuid4(), WalletTypesEnum::STORE);

        $this->assertIsObject($wallet, StoreWallet::class);
    }

    public function testMakeUserWallet()
    {
        $wallet = $this->factory->make(Uuid::uuid4(), WalletTypesEnum::USER);

        $this->assertIsObject($wallet, UserWallet::class);
    }

}
