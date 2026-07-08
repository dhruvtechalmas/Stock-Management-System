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
        Schema::create('material_requests', function (Blueprint $table) {

            $table->id();

            $table->string('request_no')->unique();

            $table->foreignId('requested_by')->constrained('users');

            $table->date('request_date');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('request_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
