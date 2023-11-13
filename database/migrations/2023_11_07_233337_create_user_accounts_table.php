<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->uuid('number')->unique();
            $table->unsignedBigInteger('holder_id')->unique();
            $table->bigInteger('balance')->default(0);
            $table->timestamps();

            $table->foreign('holder_id')->references('id')->on('users');
            $table->foreign('number')->references('uuid')->on('wallet_uuids');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_wallets');
    }
};
