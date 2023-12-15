<?php

use App\Models\Group;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Models\Incident\IncidentOnHoldReason;
use App\Models\Incident\IncidentStatus;
use App\Models\Ticket;
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
            $table->enum('category_id', IncidentCategory::MAP);
            $table->enum('item_id', IncidentItem::MAP);
            $table->enum('status_id', IncidentStatus::MAP);
            $table->enum('on_hold_reason_id', IncidentOnHoldReason::MAP)->nullable();
            $table->enum('group_id', Group::MAP);
            $table->enum('priority', Ticket::PRIORITIES);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};
