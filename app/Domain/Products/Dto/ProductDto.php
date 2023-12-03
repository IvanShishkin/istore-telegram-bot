<?php

declare(strict_types=1);

namespace App\Domain\Products\Dto;

use Spatie\LaravelData\Data;

final class ProductDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly bool $active,
        public readonly int $price,
        public readonly int $stock,
        public readonly ?string $image_path = null,
    ) {
    }
}
