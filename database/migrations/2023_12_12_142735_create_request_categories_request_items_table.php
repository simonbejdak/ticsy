<?php

use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
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
        Schema::create('request_categories_request_items', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->references('id')->on('request_categories');
            $table->foreignId('item_id')->constrained()->references('id')->on('request_items');
            $table->primary(['category_id', 'item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_categories_request_items');
    }
};
