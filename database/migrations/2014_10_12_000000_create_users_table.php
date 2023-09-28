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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email_hash')->unique(); // Hashed email, important to add index (unique) because we use this in WHERE clause.
            $table->text('email'); // Encrypted
            $table->text('tfa_secret'); // encrypted
            $table->string('email_token')->nullable();
            $table->dateTime('email_token_created_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('tfa_secret_verified_at')->nullable();
            $table->string('password')->nullable(); // Hashed
            $table->enum('role', ['root', 'admin', 'editor']);
            // $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
