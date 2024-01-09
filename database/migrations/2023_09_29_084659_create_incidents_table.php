<?php

use App\Models\Group;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\OnHoldReason;
use App\Models\Status;
use App\Traits\TicketTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id()->startingValue(10000001);
            $table->foreignId('caller_id')->constrained()->references('id')->on('users');
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->text('description');
            $table->foreignId('category_id')->constrained()->references('id')->on('incident_categories');
            $table->foreignId('item_id')->constrained()->references('id')->on('incident_items');
            $table->integer('status');
            $table->enum('on_hold_reason_id', OnHoldReason::MAP)->nullable();
            $table->enum('group_id', Group::MAP);
            $table->enum('priority', Incident::PRIORITIES);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};
