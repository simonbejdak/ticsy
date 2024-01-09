<?php

use App\Models\Group;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Models\Task;
use App\Models\Ticket;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->startingValue(10000001);
            $table->foreignId('caller_id')->constrained()->references('id')->on('users');
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->foreignId('request_id')->constrained();
            $table->text('description');
            $table->integer('status');
            $table->enum('on_hold_reason_id', OnHoldReason::MAP)->nullable();
            $table->enum('group_id', Group::MAP);
            $table->enum('priority', Task::PRIORITIES);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
