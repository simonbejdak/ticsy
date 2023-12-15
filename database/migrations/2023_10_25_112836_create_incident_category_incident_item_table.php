<?php

use App\Models\Incident\IncidentCategory;
use App\Models\Incident\IncidentItem;
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
        Schema::create('incident_category_incident_item', function (Blueprint $table) {
            $table->enum('category_id', IncidentCategory::MAP);
            $table->enum('item_id', IncidentItem::MAP);
            $table->primary(['category_id', 'item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_category_incident_item');
    }
};
