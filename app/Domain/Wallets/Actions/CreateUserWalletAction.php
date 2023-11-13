<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Actions;

use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\UserWallet;
use Ramsey\Uuid\Uuid;

final class CreateUserWalletAction
{
    /**
     * @throws WalletAlreadyExistsException
     */
    public function execute(int $userId): void
    {
        $exists = UserWalletModel::where(['holder_id' => $userId])->exists();

        if ($exists) {
            throw new WalletAlreadyExistsException('Кошелек уже существует');
        }

        $uuid = Uuid::uuid4();

        WalletUuid::create(['uuid' => $uuid]);

        UserWalletModel::create([
            'holder_id' => $userId,
            'number' => $uuid
        ]);
    }
}
