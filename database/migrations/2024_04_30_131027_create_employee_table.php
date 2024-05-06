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
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->string("nip")->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('status')->nullable(false);
            $table->date('tanggal_masuk')->nullable(false);
            $table->string('kelamin')->nullable(true);
            $table->string('tempat_lahir')->nullable(true);
            $table->date('tanggal_lahir')->nullable(true);
            $table->string('nik')->nullable(true);
            $table->string('npwp')->nullable(true);
            $table->string('provinsi')->nullable(true);
            $table->string('kabupaten')->nullable(true);
            $table->string('kecamatan')->nullable(true);
            $table->string('kelurahan')->nullable(true);
            $table->string('alamat_jalan')->nullable(true);
            $table->string('kode_pos')->nullable(true);
            $table->string('jenjang_pendidikan')->nullable(true);
            $table->string('pendidikan')->nullable(true);
            $table->unsignedBigInteger('pendidikan_id')->nullable(true);
            $table->string('status_pernikahan')->nullable(true);
            $table->string('nama_pasangan')->nullable(true);
            $table->string('nama_wali')->nullable(true);
            $table->string('nomor_darurat')->nullable(true);
            $table->string('agama')->nullable(true);
            $table->string('bpjs_tk')->nullable(true);
            $table->string('bpjs_kes')->nullable(true);
            $table->timestamps();


            $table->foreign("user_id")->on("users")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign("pendidikan_id")->on("pendidikan")->references("id")->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
