<?php

namespace App\Domain\Products\Interfaces;

use App\Domain\Products\Dto\ProductDto;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    /**
     * @param int $id
     * @return ProductDto|null
     */
    public function get(int $id): ?ProductDto;

    /**
     * @return Collection<ProductDto>
     */
    public function getAll(): Collection;
}
