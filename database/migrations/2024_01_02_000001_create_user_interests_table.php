<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_interests', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('minat', 100);
            $table->primary(['user_id', 'minat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_interests');
    }
};
