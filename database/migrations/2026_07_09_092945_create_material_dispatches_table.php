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
        Schema::create('material_dispatches', function (Blueprint $table) {
            $table->id();

            $table->string('dispatch_no')->unique();

            $table->foreignId('material_request_id')->constrained()->cascadeOnDelete();

            $table->foreignId('dispatched_by')->constrained('users')->cascadeOnDelete();

            $table->timestamp('dispatched_at')->nullable();

            $table->enum('status', [
                'pending',
                'partially_dispatched',
                'dispatched',
                'received',
                'received_with_discrepancy',
                'completed',
                'rejected',
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_dispatches');
    }
};
