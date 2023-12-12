<?php

use App\Models\RequestCategory;
use App\Models\RequestOnHoldReason;
use App\Models\RequestStatus;
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
            $table->id();
            $table->enum('category_id', RequestCategory::MAP);
            $table->enum('status_id', RequestStatus::MAP);
            $table->enum('on_hold_reason_id', RequestOnHoldReason::MAP);
            $table->foreignId('caller_id')->references('id')->on('users');
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->text('description');
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
