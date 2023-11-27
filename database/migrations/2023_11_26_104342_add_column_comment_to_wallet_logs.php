<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wallet_logs', function (Blueprint $table) {
            $table->text('comment')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('wallet_logs', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
};
