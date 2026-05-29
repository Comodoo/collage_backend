<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade')->after('registration_id');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->after('paid_at');
            $table->text('notes')->nullable()->after('processed_by');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['processed_by']);
            $table->dropColumn(['student_id', 'processed_by', 'notes']);
        });
    }
};
