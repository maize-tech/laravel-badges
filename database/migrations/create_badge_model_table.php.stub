<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badge_model', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('badge');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badge_model');
    }
};
