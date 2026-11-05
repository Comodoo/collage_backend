<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Laravel 10 requires Doctrine DBAL for ->change(); this file uses driver-aware
     * raw SQL instead. On a fresh install the credits table already declares the
     * decimal value, so the raw MODIFY is harmless/a no-op.
     */
    public function up(): void
    {
        // Change credits value from integer to decimal
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE credits MODIFY value DECIMAL(5, 1) NOT NULL DEFAULT 1');
        }

        // Add credit_id to courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('credit_id')->nullable()->after('credit_hours')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove credit_id from courses
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['credit_id']);
            $table->dropColumn('credit_id');
        });

        // Reverting the credits.decvalue type is intentionally skipped: it requires DBAL.
    }
};