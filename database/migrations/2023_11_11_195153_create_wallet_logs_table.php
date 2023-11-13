<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_logs', function (Blueprint $table) {
            $table->uuid('number');
            $table->string('operation');
            $table->bigInteger('value');
            $table->timestamps();

            $table->foreign('number')->references('uuid')->on('wallet_uuids');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_logs');
    }
};
