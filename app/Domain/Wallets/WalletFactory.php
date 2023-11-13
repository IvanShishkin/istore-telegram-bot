<?php
declare(strict_types=1);

namespace App\Domain\Wallets;

use App\Domain\Wallets\Enums\WalletTypesEnum;
use App\Domain\Wallets\Exceptions\MakeFactoryWalletException;
use App\Domain\Wallets\Interfaces\WalletInterface;

class WalletFactory
{
    /**
     * @param string $uuid
     * @param WalletTypesEnum $type
     * @return WalletInterface
     */
    public function make(string $uuid, WalletTypesEnum $type): WalletInterface
    {
        return match ($type) {
            WalletTypesEnum::USER => new UserWallet($uuid),
            WalletTypesEnum::STORE => new StoreWallet($uuid),
            default => new MakeFactoryWalletException('Не известный тип кошелька')
        };
    }
}
