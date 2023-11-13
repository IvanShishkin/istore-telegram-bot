<?php

namespace App\Domain\Wallets\Enums;

enum WalletTypesEnum: string
{
    case USER = 'user';
    case STORE = 'store';
}
