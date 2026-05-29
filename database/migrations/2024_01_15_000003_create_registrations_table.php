<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->nullable()->unique();
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            
            // National ID Information
            $table->string('national_id');
            $table->enum('national_id_type', ['passport', 'national_id', 'birth_certificate'])->default('national_id');
            $table->date('national_id_expiry_date')->nullable();
            
            // Program Information
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('program_name');
            $table->string('department');
            $table->string('intake');
            $table->enum('study_mode', ['full_time', 'part_time', 'distance_learning']);
            
            // Guardian Information
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->string('guardian_email');
            $table->string('guardian_relationship');
            $table->string('guardian_address');
            
            // Status
            $table->enum('status', ['pending', 'payment_completed', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
