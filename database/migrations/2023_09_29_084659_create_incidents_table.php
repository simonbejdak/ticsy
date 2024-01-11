<?php

use App\Models\Group;
use App\Models\Incident;
use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
use App\Enums\OnHoldReason;
use App\Enums\Status;
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
            $table->string('status');
            $table->string('on_hold_reason')->nullable();
            $table->foreignId('group_id')->constrained();
            $table->integer('priority');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};
