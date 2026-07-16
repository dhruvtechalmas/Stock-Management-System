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
        Schema::table('material_consumptions', function (Blueprint $table) {
            $table->decimal('remaining_qty', 14, 3)->default(0)->after('consumed_qty');
        });
    }

    public function down(): void
    {
        Schema::table('material_consumptions', function (Blueprint $table) {
            $table->dropColumn('remaining_qty');
        });
    }
};
