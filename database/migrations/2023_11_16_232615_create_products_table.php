<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->text('description');
            $table->integer('price');
            $table->integer('stock');
            $table->text('image_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
