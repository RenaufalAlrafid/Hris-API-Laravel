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
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(false);
            $table->boolean("atasan")->nullable(false);
            $table->boolean('validator')->nullable(false);
            $table->unsignedBigInteger('divisi_id')->nullable(false);
            $table->timestamps();

            $table->foreign('divisi_id')->references('id')->on('divisi')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};
