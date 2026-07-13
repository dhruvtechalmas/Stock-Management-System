<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE material_dispatches MODIFY status ENUM('pending','partially_dispatched','dispatched','received','received_with_discrepancy','completed','rejected') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE material_dispatches MODIFY status ENUM('pending','dispatched','received','received_with_discrepancy','completed') NOT NULL DEFAULT 'pending'");
    }
};
