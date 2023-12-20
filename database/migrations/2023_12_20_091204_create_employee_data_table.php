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
        Schema::create('employee_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable(false);
            $table->string('kelamin')->nullable(false);
            $table->string('tempat_lahir')->nullable(false);
            $table->date('tanggal_lahir')->nullable(false);
            $table->string('nik')->nullable(false);
            $table->string('npwp')->nullable(true);
            $table->string('provinsi')->nullable(false);
            $table->string('kabupaten')->nullable(false);
            $table->string('kecamatan')->nullable(true);
            $table->string('kelurahan')->nullable(false);
            $table->string('alamat_jalan')->nullable(true);
            $table->string('kode_pos')->nullable(true);
            $table->string('jenjang_pendidikan')->nullable(false);
            $table->unsignedBigInteger('pendidikan_id')->nullable(false);
            $table->string('status')->nullable(false);
            $table->string('nama_pasangan')->nullable(true);
            $table->string('nama_wali')->nullable(false);
            $table->string('nomor_darurat')->nullable(false);
            $table->string('agama')->nullable(true);
            $table->string('bpjs_tk')->nullable(true);
            $table->string('bpjs_kes')->nullable(true);
            $table->timestamps();
            $table->foreign("employee_id")->on("employees")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_data');
    }
};
