<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CariKata extends Model
{
    use HasFactory;

    protected $table = 'cari_kata';

    protected $fillable = [
        'cerita_id', 'user_id', 'score', 'total_words', 'found_words', 'attempts', 'time_seconds', 'grid', 'solution', 'state', 'completed_at'
    ];

    protected $casts = [
        'grid' => 'array',
        'solution' => 'array',
        'state' => 'array',
        'completed_at' => 'datetime'
    ];

    public function cerita()
    {
        return $this->belongsTo(Cerita::class, 'cerita_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function template()
    {
        return $this->belongsTo(CariKataTemplate::class, 'template_id');
    }
}
