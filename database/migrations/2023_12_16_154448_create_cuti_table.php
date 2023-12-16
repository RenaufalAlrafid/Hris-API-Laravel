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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable(false);
            $table->date('date_start')->nullable(false);
            $table->date('date_end')->nullable(false);
            $table->integer('days')->nullable(false);
            $table->string('description')->nullable(true);
            $table->boolean('approve_hrd')->nullable(false);
            $table->boolean('approve_atasan')->nullable(false);
            $table->timestamps();
            $table->foreign("employee_id")->on("employees")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
