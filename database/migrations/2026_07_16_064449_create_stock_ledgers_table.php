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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            
            $table->id();

            $table->foreignId('material_id')->constrained()->cascadeOnDelete();

            $table->enum('transaction_type', ['purchase', 'dispatch', 'receive', 'consumption', 'wastage', 'adjustment',]);

            // Related record (Purchase, Dispatch, Consumption, Wastage, etc.)
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');

            // Stock movement
            $table->decimal('qty_in', 14, 3)->default(0);
            $table->decimal('qty_out', 14, 3)->default(0);

            // Material stock after this transaction
            $table->decimal('balance_after', 14, 3);

            // Transaction date
            $table->date('transaction_date');

            // Optional remarks
            $table->text('remarks')->nullable();

            // User who performed the transaction
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index(['material_id', 'transaction_date']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
