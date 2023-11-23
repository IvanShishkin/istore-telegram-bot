<?php

namespace Tests\Unit\Domain\Traits;

use App\Domain\User\Models\User;
use App\Domain\Wallets\Models\StoreBalanceModel;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;

trait WalletMockTrait
{
    protected function mockUserWalletModel($additionalMockValues = []): UserWalletModel
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

    protected function mockStoreWalletModel($additionalMockValues = []): StoreBalanceModel
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
}
