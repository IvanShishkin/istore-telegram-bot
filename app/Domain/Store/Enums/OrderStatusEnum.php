<?php

namespace App\Domain\Store\Enums;

enum OrderStatusEnum: string
{
    case NEW = 'new';
    case IN_PROCESSING = 'in_processing';
    case PROCESSED = 'processed';
    case CANCEL = 'cancel';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::IN_PROCESSING => 'В обработке',
            self::PROCESSED => 'Выполнен',
            self::CANCEL => 'Отменен',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NEW => '🟦',
            self::IN_PROCESSING => '🟨',
            self::PROCESSED => '🟩',
            self::CANCEL => '🟥',
        };
    }
}
