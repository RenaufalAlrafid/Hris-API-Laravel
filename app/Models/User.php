<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;
    protected $fillable = [
        "username",
        "password",
        "email",
        "token",
        "jabatan_id",
        "validation",
    ];

    public function jabatan() : BelongsTo {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', "id");
    }

    public function employee(): HasOne {
        return $this->hasOne(Employee::class, 'user_id', 'id');
        
    }
}
