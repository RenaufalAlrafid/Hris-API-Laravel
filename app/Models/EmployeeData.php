<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeData extends Model
{
    use HasFactory;
    protected $table = "employee_datas";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "employee_id",
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
        "status",
        "nama_pasangan",
        "nama_wali",
        "nomor_darurat",
        "agama",
        "bpjs_tk",
        "bpjs_kes"
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function pendidikan() : BelongsTo {
        return $this->belongsTo(Pendidikan::class, "pendidikan_id", "id");
    }

}
