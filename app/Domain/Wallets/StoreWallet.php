<?php

declare(strict_types=1);

namespace App\Domain\Wallets;

use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Models\StoreBalanceModel;

class StoreWallet extends AbstractWallet
{
    protected ?WalletTypesEnum $type = WalletTypesEnum::STORE;

    public function init(string $uuid): StoreBalanceModel
    {
        $model = Models\StoreBalanceModel::where(['number' => $uuid])->lockForUpdate()->first();

        if (!$model) {
            throw new InitializationException('Ошибка инициализации баланса клиента');
        }

        return $model;
    }
}
