<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendidikan extends Model
{
    use HasFactory;
    protected $table = "pendidikans";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "name"
    ];

    public function empployeeData() : HasMany {
        return $this->hasMany(EmployeeData::class, 'pendidika_id', "id");
    }
}
