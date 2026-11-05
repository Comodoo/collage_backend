<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->foreignId('submitted_by')->nullable()->after('recorded_by')->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable()->after('submitted_by');
            $table->foreignId('reviewed_by')->nullable()->after('submitted_at')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('rejection_reason')->nullable()->after('reviewed_at');
        });

        // Expand the status enum to support the draft -> submitted -> published / rejected workflow.
        // Laravel 10 needs doctrine/dbal for ->change(), which is not installed, so use driver-aware SQL.
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("ALTER TABLE exam_results MODIFY status VARCHAR(20) NOT NULL DEFAULT 'draft'");
            return;
        }

        if ($driver === 'sqlite') {
            $this->rebuildSqliteStatusColumn();
        }
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submitted_by');
            $table->dropColumn('submitted_at');
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn('reviewed_at');
            $table->dropColumn('rejection_reason');
        });
    }

    /**
     * SQLite stores enums as a CHECK constraint, which cannot be altered in place.
     * Rebuild the table with a plain string status column while preserving all rows.
     */
    private function rebuildSqliteStatusColumn(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::rename('exam_results', 'exam_results_old');

        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_offering_id')->constrained()->onDelete('cascade');
            $table->decimal('cat1_score', 5, 2)->nullable();
            $table->decimal('cat2_score', 5, 2)->nullable();
            $table->decimal('assignment_score', 5, 2)->nullable();
            $table->decimal('final_exam_score', 5, 2);
            $table->decimal('total_score', 5, 2);
            $table->string('grade', 3);
            $table->decimal('gpa_points', 3, 2);
            $table->string('status', 20)->default('draft');
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        $columns = 'id, student_id, course_offering_id, cat1_score, cat2_score, assignment_score, '
            . 'final_exam_score, total_score, grade, gpa_points, status, remarks, recorded_by, '
            . 'submitted_by, submitted_at, reviewed_by, reviewed_at, rejection_reason, published_at, '
            . 'created_at, updated_at';

        DB::statement("INSERT INTO exam_results ({$columns}) SELECT {$columns} FROM exam_results_old");

        Schema::drop('exam_results_old');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};
