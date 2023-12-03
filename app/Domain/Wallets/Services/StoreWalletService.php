<?php

declare(strict_types=1);

namespace App\Domain\Wallets\Services;

use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletAlreadyExistsException;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\StoreBalanceModel;
use App\Domain\Wallets\Models\WalletUuid;
use App\Domain\Wallets\StoreWallet;
use Ramsey\Uuid\Uuid;

final class StoreWalletService
{
    /**
     * @throws WalletNotExistsException
     */
    public function initWallet(): StoreWallet
    {
        $wallet = $this->find();

        return new StoreWallet($wallet->number);
    }

    /**
     * @throws WalletNotExistsException
     */
    public function getWalletData(): WalletDto
    {
        $wallet = $this->find();

        return $this->makeDto($wallet);
    }

    /**
     * @throws WalletAlreadyExistsException
     */
    public function create(): WalletDto
    {
        $exists = StoreBalanceModel::take(1)->exists();

        if ($exists) {
            throw new WalletAlreadyExistsException('Кошелек уже существует');
        }

        $uuid = Uuid::uuid4();

        WalletUuid::create(['uuid' => $uuid]);

        $model = StoreBalanceModel::create([
            'balance' => 0,
            'number' => $uuid
        ]);

        return $this->makeDto($model);
    }

    protected function makeDto(StoreBalanceModel $model): WalletDto
    {
        return WalletDto::from($model->toArray());
    }

    public function exists(): bool
    {
        return StoreBalanceModel::take(1)->exists();
    }


    /**
     * @throws WalletNotExistsException
     */
    protected function find(): StoreBalanceModel
    {
        $wallet = StoreBalanceModel::take(1)->first();

        if (!$wallet) {
            throw new WalletNotExistsException('Кошелек магазина не найден');
        }

        return $wallet;
    }
}
