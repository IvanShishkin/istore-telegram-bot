<?php

namespace App\Domain\Products\Interfaces;

use App\Domain\Products\Dto\ProductDto;

interface StockRepositoryInterface
{
    public function increaseStock(int $id): ?ProductDto;

    public function reduceStock(int $id): ?ProductDto;
}
