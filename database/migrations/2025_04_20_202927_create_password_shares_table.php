<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_shares', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('password');
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('remaining_uses')->default(1);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_shares');
    }
};
