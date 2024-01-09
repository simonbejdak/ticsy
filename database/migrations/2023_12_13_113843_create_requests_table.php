<?php

use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Request;
use App\Models\Request\RequestCategory;
use App\Models\Request\RequestItem;
use App\Models\Status;
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
            $table->integer('status');
            $table->enum('on_hold_reason_id', OnHoldReason::MAP)->nullable();
            $table->enum('group_id', Group::MAP);
            $table->enum('priority', Request::PRIORITIES);
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
