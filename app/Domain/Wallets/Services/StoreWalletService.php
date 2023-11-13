<?php
declare(strict_types=1);

namespace App\Domain\Wallets\Services;

use App\Domain\Wallets\Dto\WalletDto;
use App\Domain\Wallets\Exceptions\WalletNotExistsException;
use App\Domain\Wallets\Models\StoreBalanceModel;
use App\Domain\Wallets\Models\UserWalletModel;
use App\Domain\Wallets\StoreWallet;

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

    public function getWalletData(): WalletDto
    {
       $wallet = $this->find();

       return $this->makeDto($wallet);
    }

    protected function makeDto(StoreBalanceModel $model): WalletDto
    {
        return WalletDto::from($model->toArray());
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
