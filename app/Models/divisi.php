<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;
    protected $table = "divisis";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "name"
    ];

    public function jabatans(): HasMany {
        return $this->hasMany(jabatan::class, 'divisi_id', 'id');
    }
}
