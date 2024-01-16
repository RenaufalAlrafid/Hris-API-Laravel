<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatans';
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "name",
        "divisi_id",
        "atasan",
        "validator",
    ];

    public function divisi() : BelongsTo {
        return $this->belongsTo(divisi::class, "divisi_id", "id");
    }

    public function users() : HasMany {
        return $this->hasMany(User::class, "jabatan_id", "id");
    }
}
