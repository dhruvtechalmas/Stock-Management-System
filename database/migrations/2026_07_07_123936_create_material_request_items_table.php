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
        Schema::create('material_request_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('material_request_id')->constrained()->cascadeOnDelete();

            $table->foreignId('material_id')->constrained();

            $table->decimal('requested_qty', 14, 3);

            $table->timestamps();

            $table->index(['material_request_id', 'material_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_request_items');
    }
};
