<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('store_balances', function (Blueprint $table) {
            $table->id();
            $table->uuid('number')->unique();
            $table->bigInteger('balance')->default(0);
            $table->timestamps();

            $table->foreign('number')->references('uuid')->on('wallet_uuids');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_balances');
    }
};
