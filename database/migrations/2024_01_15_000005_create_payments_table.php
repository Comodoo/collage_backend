<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->enum('fee_type', ['registration_fee', 'tuition_fee', 'library_fee', 'laboratory_fee', 'examination_fee', 'hostel_fee', 'other']);
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('KES');
            $table->string('control_number')->unique();
            $table->enum('method', ['mpesa', 'card', 'bank_transfer', 'cash']);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
