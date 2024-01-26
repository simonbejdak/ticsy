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
        Schema::create('requests', function (Blueprint $table) {
            $table->id()->startingValue(10000001);
            $table->foreignId('caller_id')->references('id')->on('users');
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->text('description');
            $table->foreignId('category_id')->constrained()->references('id')->on('request_categories');
            $table->foreignId('item_id')->constrained()->references('id')->on('request_items');
            $table->string('status');
            $table->string('on_hold_reason')->nullable();
            $table->foreignId('group_id')->constrained();
            $table->integer('priority');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
