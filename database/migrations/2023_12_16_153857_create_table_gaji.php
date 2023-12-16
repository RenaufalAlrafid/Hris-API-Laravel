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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->nullable(false);
            $table->integer('bulan')->nullable(false);
            $table->unsignedBigInteger('employee_id')->nullable(false);
            $table->integer('gaji_pokok')->nullable(false);
            $table->integer('tambahan')->nullable(true);
            $table->integer('potongan')->nullable(true);
            $table->integer('total')->nullable(false);
            $table->timestamps();
            $table->foreign("employee_id")->on("employees")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};
