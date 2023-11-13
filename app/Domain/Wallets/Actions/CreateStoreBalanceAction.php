<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Actions;

use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Models\StoreBalanceModel;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use Ramsey\Uuid\Nonstandard\Uuid;

class CreateStoreBalanceAction
{
    /**
     * @throws WalletAlreadyExistsException
     */
    public function execute(int $balance = 0): void
    {
        $exists = StoreBalanceModel::take(1)->exists();

        if ($exists) {
            throw new WalletAlreadyExistsException('Кошелек уже существует');
        }

        $uuid = \Ramsey\Uuid\Uuid::uuid4();

        WalletUuid::create(['uuid' => $uuid]);

        StoreBalanceModel::create([
            'balance' => $balance,
            'number' => $uuid
        ]);
    }
}
