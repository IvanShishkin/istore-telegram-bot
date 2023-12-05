<?php

namespace App\Domain\Сurrency\Payments\Enums;

enum PaymentStatusEnum: string
{
    case NEW = 'new';
    case SUCCESS = 'success';
    case CANCEL = 'cancel';
}
