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
      $table->id()->comment('Unique identifier');
      $table->string('username', 64)->unique()->comment('Unique username');
      $table->string('avatar', 512)->nullable()->comment('URL for users avatar');
      $table->string('email', 256)->unique()->comment('Unique email address');
      $table->timestamp('email_verified_at')->nullable()->comment('Email verification timestamp');
      $table->string('password')->comment('Password hash for authorization');
      $table->date('birth_date')->default('1900-01-01')->comment('Date of birth');
      $table->rememberToken()->comment('Remember token for "remember me" functionality');
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
