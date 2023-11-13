<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('status');
            $table->integer('value');
            $table->dateTime('term_at')->nullable();
            $table->boolean('with_error')->default(false);
            $table->text('error_detail')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id');
            $table->string('wallet_number');
            $table->string('direction');
            $table->string('type');
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions');
        });


    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign('transaction_items_transaction_id_foreign');
        });
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('transaction_items');
    }
};
