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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['tuition', 'direct']);
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->enum('applicable_semester', ['semester_1', 'semester_2', 'both'])->default('both');
            $table->decimal('semester_1_amount', 12, 2)->default(0);
            $table->decimal('semester_2_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency')->default('TZS');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
