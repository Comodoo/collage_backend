<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $hasOperations = DB::table('information_schema.columns')
                ->where('table_schema', DB::connection()->getDatabaseName())
                ->where('table_name', 'users')
                ->where('column_name', 'role')
                ->where('column_type', 'like', '%operations%')
                ->exists();

            if (!$hasOperations) {
                DB::statement("ALTER TABLE users MODIFY role ENUM('student', 'instructor', 'admin', 'accountant', 'operations') NOT NULL DEFAULT 'student'");
            }
            return;
        }

        if ($driver === 'sqlite') {
            $columnSql = DB::selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='users'")->sql ?? '';
            if (!str_contains($columnSql, 'operations')) {
                $this->rebuildSqliteUsers();
            }
        }
    }

    public function down(): void
    {
        // Intentionally no-op.
    }

    private function rebuildSqliteUsers(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        Schema::rename('users', 'users_old');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['student', 'instructor', 'admin', 'accountant', 'operations'])->default('student');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('INSERT INTO users (id, first_name, last_name, email, email_verified_at, password, role, phone, date_of_birth, gender, remember_token, created_at, updated_at) SELECT id, first_name, last_name, email, email_verified_at, password, role, phone, date_of_birth, gender, remember_token, created_at, updated_at FROM users_old');

        DB::statement('UPDATE sqlite_sequence SET seq = (SELECT MAX(id) FROM users) WHERE name = "users"');

        Schema::drop('users_old');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};