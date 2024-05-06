<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $table = "employee";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "user_id",
        "nip",
        "name",
        "status",
        "tanggal_masuk",
        "kelamin",
        "tempat_lahir",
        "tanggal_lahir",
        "nik",
        "npwp",
        "provinsi",
        "kabupaten",
        "kecamatan",
        "kelurahan",
        "alamat_jalan",
        "kode_pos",
        "jenjang_pendidikan",
        "pendidikan_id",
        "status_pernikahan",
        "nama_pasangan",
        "nama_wali",
        "nomor_darurat",
        "agama",
        "bpjs_tk",
        "bpjs_kes"
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function pendidikan(): BelongsTo {
        return $this->belongsTo(Pendidikan::class, "pendidikan_id", "id");
    }


    public function jabatan(): BelongsTo {
        // Assuming the relationship between Employee and Jabatan is through the User model
        return $this->user->jabatan();
    }
}
