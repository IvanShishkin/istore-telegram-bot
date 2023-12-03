<?php

declare(strict_types=1);

namespace App\Domain\Wallets\Services;

use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\UserWallet;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UserWalletService
{
    /**
     * @throws WalletNotExistsException
     */
    public function getDataByNumber(string $number): WalletDto
    {
        $wallet = UserWalletModel::where(['number' => $number])->first();

        if (!$wallet) {
            throw new WalletNotExistsException('Кошелек не найден');
        }

        return $this->makeDto($wallet);
    }

    /**
     * @throws WalletNotExistsException
     */
    public function getWalletData(int $holderId): WalletDto
    {
        $wallet = UserWalletModel::where(['holder_id' => $holderId])->first();

        if (!$wallet) {
            throw new WalletNotExistsException('Кошелек не найден');
        }

        return $this->makeDto($wallet);
    }

    /**
     * @param int $userId
     * @return WalletDto
     * @throws WalletAlreadyExistsException
     */
    public function create(int $userId): WalletDto
    {
        $exists = UserWalletModel::where(['holder_id' => $userId])->exists();

        if ($exists) {
            throw new WalletAlreadyExistsException('Кошелек уже существует');
        }

        $uuid = Uuid::uuid4();

        WalletUuid::create(['uuid' => $uuid]);

        $model = UserWalletModel::create([
            'holder_id' => $userId,
            'number' => $uuid,
            'balance' => 0
        ]);

        return $this->makeDto($model);
    }

    /**
     * @throws WalletNotExistsException
     */
    public function initWallet(int $holderId): UserWallet
    {
        $walletDto = $this->getWalletData($holderId);

        return new UserWallet($walletDto->number);
    }

    protected function makeDto(UserWalletModel $model): WalletDto
    {
        return WalletDto::from($model->toArray());
    }
}
