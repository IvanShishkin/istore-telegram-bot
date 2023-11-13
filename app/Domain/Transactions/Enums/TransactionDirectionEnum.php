<?php

namespace App\Domain\Transactions\Enums;

enum TransactionDirectionEnum: string
{
    case FROM = 'from';
    case TO = 'to';
}
