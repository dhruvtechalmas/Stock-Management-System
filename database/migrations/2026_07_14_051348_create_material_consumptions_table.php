<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_dispatch_item_id')->constrained('material_dispatch_items');
            $table->foreignId('material_id')->constrained('materials');
            $table->decimal('consumed_qty', 14, 3); // must be <= dispatched_qty, enforced in service layer
            $table->date('consumption_date');
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamps();

            $table->index(['material_id', 'consumption_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_consumptions');
    }
};
