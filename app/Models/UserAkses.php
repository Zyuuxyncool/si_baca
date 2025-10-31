<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAkses extends Model
{
    use HasFactory;

    protected $table = 'user_akses';
    protected $fillable = [
        'user_id',
        'akses',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
