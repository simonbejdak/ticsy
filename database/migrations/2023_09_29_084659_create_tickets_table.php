<?php

use App\Models\Ticket;
use App\Models\TicketConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->startingValue(10000001);
            $table->foreignId('type_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('on_hold_reason_id')->nullable()->constrained();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->enum('priority', Ticket::PRIORITIES)->default(Ticket::DEFAULT_PRIORITY);
            $table->text('priority_reason')->nullable();
            $table->text('description');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
