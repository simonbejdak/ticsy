<?php

use App\Models\Ticket;
use App\Models\TicketConfiguration;
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
            $table->foreignId('status_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('resolver_id')->nullable()->constrained()->references('id')->on('users');
            $table->enum('priority', TicketConfiguration::PRIORITIES)->default(TicketConfiguration::DEFAULT_PRIORITY);
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
