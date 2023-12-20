<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;
    protected $table = "employees";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "user_id",
        "nip",
        "name",
        "status",
        "tanggal_masuk"
    ];

    public function gaji(): HasMany {
        return $this->hasMany(Gaji::class, "employee_id", "id");
    }

    public function cuti(): HasMany {
        return $this->hasMany(Cuti::class, "employee_id", "id");
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function employeeData(): HasOne {
        return $this->hasOne(EmployeeData::class, "employee_id", "id");
    }
}
