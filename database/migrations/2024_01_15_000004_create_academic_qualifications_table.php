<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['o_level', 'a_level', 'certificate', 'diploma', 'degree', 'other']);
            $table->string('institution_name');
            $table->text('institution_address');
            $table->string('country');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('examination_board')->nullable();
            $table->string('index_number')->nullable();
            $table->string('grade')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('major')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_qualifications');
    }
};
