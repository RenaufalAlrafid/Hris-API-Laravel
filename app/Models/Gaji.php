<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gaji extends Model
{
    use HasFactory;
    protected $table = "gajis";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "tahun",
        "bulan",
        "employee_id",
        "gaji_pokok",
        "tambahan",
        "potongan",
        "total"
    ];
    // Relasi ke tabel Employee

    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }
}
