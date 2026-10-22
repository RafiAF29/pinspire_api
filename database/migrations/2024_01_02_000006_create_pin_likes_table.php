<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pin_likes', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pin_id')->constrained()->cascadeOnDelete();
            $table->timestamp('liked_at')->useCurrent();
            $table->primary(['user_id', 'pin_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pin_likes');
    }
};
