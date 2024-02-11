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
        Schema::create('configuration_items', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('location');
            $table->string('operating_system');
            $table->string('status');
            $table->string('type');
            $table->foreignId('group_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_items');
    }
};
