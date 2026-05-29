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
        // Change credits value from integer to decimal
        Schema::table('credits', function (Blueprint $table) {
            $table->decimal('value', 5, 1)->change();
        });

        // Add credit_id to courses table (department_id already exists)
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

        // Revert credits value to integer
        Schema::table('credits', function (Blueprint $table) {
            $table->integer('value')->change();
        });
    }
};
