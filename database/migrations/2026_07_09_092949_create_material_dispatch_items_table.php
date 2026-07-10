<?php

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
        Schema::create('material_dispatch_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('material_dispatch_id')->constrained()->cascadeOnDelete();

            $table->foreignId('material_request_item_id')->constrained()->cascadeOnDelete();

            $table->foreignId('material_id')->constrained()->cascadeOnDelete();

            $table->decimal('dispatched_qty',10,2);

            $table->decimal('received_qty',10,2)->default(0);

            $table->decimal('missing_qty',10,2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_dispatch_items');
    }
};