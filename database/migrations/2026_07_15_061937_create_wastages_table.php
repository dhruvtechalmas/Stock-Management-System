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
        Schema::create('wastages', function (Blueprint $table) {

            $table->id();

            $table->string('wastage_no')->unique();

            $table->foreignId('material_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->decimal('quantity', 14, 3);

            $table->text('reason');

            $table->date('wastage_date');

            $table->foreignId('recorded_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('reference_type')->nullable();
            
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->timestamps();

            $table->index(['material_id', 'wastage_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wastages');
    }
};
