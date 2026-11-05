<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_grade_id')->constrained('salary_grades')->cascadeOnDelete();
            $table->integer('step_number');
            $table->decimal('basic_salary', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['salary_grade_id', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_steps');
    }
};