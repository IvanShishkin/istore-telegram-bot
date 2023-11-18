<?php

namespace App\Domain\Store\Enums;

enum OrderStatusEnum: string
{
    case NEW = 'new';
    case IN_PROCESSING = 'in_processing';
    case PROCESSED = 'processed';
    case CANCEL = 'cancel';
}
