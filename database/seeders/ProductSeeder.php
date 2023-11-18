<?php

namespace Database\Seeders;

use App\Domain\Products\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    //todo остановился тут не отрабоота ситтер
    public function run(): void
    {
        Product::factory()->count(10)->create();
    }
}
