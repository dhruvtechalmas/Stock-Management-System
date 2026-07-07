<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            $table->string('purchase_no')->unique();

            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();

            $table->string('invoice_no')->nullable();

            $table->date('purchase_date');

            $table->decimal('total_amount', 14, 2)->default(0);

            $table->text('remarks')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            $table->index('purchase_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};