<?php
declare(strict_types=1);

namespace App\Domain\Products\Services;

use App\Domain\Products\Dto\ProductDto;
use App\Domain\Products\Exceptions\ErrorStockChangeException;
use App\Domain\Products\Exceptions\NotFoundException;
use App\Domain\Products\Interfaces\ProductRepositoryInterface;
use App\Domain\Products\Interfaces\StockRepositoryInterface;
use Illuminate\Support\Collection;

final class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected StockRepositoryInterface $stockRepository
    )
    {
    }

    /**
     * @param int $id
     * @return ProductDto
     * @throws NotFoundException
     */
    public function get(int $id): ProductDto
    {
        $product = $this->productRepository->get($id);

        if (empty($product)) {
            throw new NotFoundException('Товар не найден');
        }

        return $product;
    }

    /**
     * @return Collection<ProductDto>
     */
    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    /**
     * @param int $id
     * @return ProductDto
     * @throws ErrorStockChangeException
     */
    public function increaseStock(int $id): ProductDto
    {
        $changed = $this->stockRepository->increaseStock($id);

        if (!$changed) {
            throw new ErrorStockChangeException("Ошибка увеличения количества товара id:{$id}");
        }

        return $changed;
    }

    /**
     * @param int $id
     * @return ProductDto
     * @throws ErrorStockChangeException
     */
    public function reduceStock(int $id): ProductDto
    {
        $changed = $this->stockRepository->reduceStock($id);

        if (!$changed) {
            throw new ErrorStockChangeException("Ошибка уменьшения количества товара id:{$id}");
        }

        return $changed;
    }
}
