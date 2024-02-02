<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_resolver_panel_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('option');
            $table->unique(['user_id', 'option']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_resolver_panel_options');
    }
};
