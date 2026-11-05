<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes for the columns the most frequent list/filter queries
     * use, so scans turn into index seeks as the tables grow.
     */
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->index('status');
            $table->index(['student_id', 'status']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('status');
            $table->index(['registration_id', 'status']);
        });

        Schema::table('instructor_assignments', function (Blueprint $table) {
            $table->index(['instructor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'status']);
            $table->dropIndex(['status']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['registration_id', 'status']);
            $table->dropIndex(['status']);
        });

        Schema::table('instructor_assignments', function (Blueprint $table) {
            $table->dropIndex(['instructor_id', 'status']);
        });
    }
};