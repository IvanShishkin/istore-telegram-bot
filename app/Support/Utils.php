<?php
declare(strict_types=1);

namespace App\Support;

use Ramsey\Uuid\Uuid;

class Utils
{
    public static function errorInitializationCode(): string
    {
        return Uuid::uuid4()->toString();
    }
}
