<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_dispatches', function (Blueprint $table) {

            $table->text('remarks')
                ->nullable()
                ->after('status');

            $table->timestamp('received_at')
                ->nullable()
                ->after('dispatched_at');

            $table->foreignId('received_by')
                ->nullable()
                ->after('received_at')
                ->constrained('users');

            $table->timestamp('resolved_at')
                ->nullable()
                ->after('received_by');

            $table->foreignId('resolved_by')
                ->nullable()
                ->after('resolved_at')
                ->constrained('users');

        });
    }

    public function down(): void
    {
        Schema::table('material_dispatches', function (Blueprint $table) {

            $table->dropForeign(['received_by']);
            $table->dropForeign(['resolved_by']);

            $table->dropColumn([
                'remarks',
                'received_at',
                'received_by',
                'resolved_at',
                'resolved_by'
            ]);

        });
    }
};