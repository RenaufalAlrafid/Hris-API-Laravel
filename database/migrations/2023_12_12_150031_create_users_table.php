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
            $table->string("username")->nullable(false)->unique("users_username_unique");
            $table->string("password")->nullable(false);
            $table->string("email")->unique("users_email_unique")->nullable(false);
            $table->string("token", 100)->nullable()->unique(
                "users_token_unique"
            );
            $table->unsignedBigInteger("jabatan_id")->nullable(false);
            $table->boolean('validation')->nullable(false)->default(false);
            $table->timestamps();
            $table->foreign("jabatan_id")->on("jabatans")->references("id");
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
