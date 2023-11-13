<?php
declare(strict_types=1);

namespace App\Domain\Wallets;

use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\FailedSaveException;
use App\Domain\Wallets\Exceptions\InitializationException;
use App\Domain\Wallets\Exceptions\ReduceBalanceException;
use App\Domain\Wallets\Interfaces\WalletInterface;

class UserWallet extends AbstractWallet
{
    protected ?WalletTypesEnum $type = WalletTypesEnum::USER;
    /**
     * @throws InitializationException
     */
    public function init(string $uuid): Models\UserWalletModel
    {
        $model = Models\UserWalletModel::where([
            'number' => $uuid
        ])->lockForUpdate()->first();

        if (!$model) {
            throw new InitializationException('Ошибка инициализации баланса клиента');
        }

        return $model;
    }
}
