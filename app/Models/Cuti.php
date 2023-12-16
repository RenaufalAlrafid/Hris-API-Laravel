<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
    use HasFactory;
    protected $table = "cutis";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "employee_id",
        "date_start",
        "date_end",
        "dayas",
        "description",
        "approve_hrd",
        "approve_atasan"
    ];
    
    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }
}
