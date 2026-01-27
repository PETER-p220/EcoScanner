<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('face_encoding')->nullable();
            $table->string('face_image_path')->nullable();
            $table->integer('points')->default(0);
            $table->integer('level')->default(1);
            $table->integer('total_scans')->default(0);
            $table->integer('total_reports')->default(0);
            $table->integer('ewaste_count')->default(0);
            $table->string('location')->nullable();
            $table->string('language_preference')->default('en');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};