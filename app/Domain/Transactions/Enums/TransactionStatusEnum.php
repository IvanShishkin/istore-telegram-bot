<?php

namespace App\Domain\Transactions\Enums;

enum TransactionStatusEnum: string
{
    case NEW = 'new';
    case COMPLETED = 'completed';
    case CLOSED = 'closed';
}
