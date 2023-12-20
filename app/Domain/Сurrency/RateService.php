<?php

declare(strict_types=1);

namespace App\Domain\Сurrency;

final class RateService
{
    public const RATE_RUBLE = 2;

    public function convertToRuble(int $amount): float|int
    {
        return $amount * self::RATE_RUBLE;
    }
}
