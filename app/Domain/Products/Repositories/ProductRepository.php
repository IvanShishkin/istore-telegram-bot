<?php

declare(strict_types=1);

namespace App\Domain\Products\Repositories;

use App\Domain\Products\Dto\ProductDto;
use App\Domain\Products\Interfaces\ProductRepositoryInterface;
use App\Domain\Products\Interfaces\StockRepositoryInterface;
use App\Domain\Products\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface, StockRepositoryInterface
{
    public function get(int $id): ?ProductDto
    {
        $model = $this->getModel($id);

        if (!$model) {
            return null;
        }

        return self::makeProductDto($model);
    }

    /**
     * @return Collection<ProductDto>
     */
    public function getAll(): Collection
    {
        $return = new Collection();

        $get = Product::where(['active' => true])->get();

        if ($get->isNotEmpty()) {
            $return = $get->map(fn (Product $model) => self::makeProductDto($model));
        }

        return $return;
    }

    public function increaseStock(int $id): ?ProductDto
    {
        $result = null;

        \DB::beginTransaction();

        if ($model = $this->getModel($id)) {
            $model->stock = $model->stock + 1;

            if ($model->save()) {
                \DB::commit();
                $result = self::makeProductDto($model);
            }
        }

        if (!$result) {
            \DB::rollBack();
        }

        return $result;
    }

    public function reduceStock(int $id): ?ProductDto
    {
        $result = null;

        \DB::beginTransaction();

        if ($model = $this->getModel($id)) {
            $currentValue = $model->stock;

            $model->stock = $currentValue - 1;

            if ($model->save()) {
                \DB::commit();
                $result = self::makeProductDto($model);
            }
        }

        if (!$result) {
            \DB::rollBack();
        }

        return $result;
    }

    protected function getModel(int $id): ?Product
    {
        return Product::where(['id' => $id])->lockForUpdate()->first();
    }

    protected static function makeProductDto(Product $model): ProductDto
    {
        return ProductDto::from($model->toArray());
    }
}
