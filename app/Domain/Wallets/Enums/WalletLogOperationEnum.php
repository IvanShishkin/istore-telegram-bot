<?php

namespace App\Domain\Wallets\Enums;

enum WalletLogOperationEnum: string
{
    case INCREASE = 'increase';
    case REDUCE = 'reduce';
}
