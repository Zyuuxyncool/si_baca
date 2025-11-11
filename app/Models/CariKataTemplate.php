<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CariKataTemplate extends Model
{
    use HasFactory;

    protected $table = 'cari_kata_templates';

    protected $fillable = [
        'cerita_id', 'title', 'slug', 'active', 'difficulty', 'grid_rows', 'grid_cols', 'directions', 'allow_overlap', 'points_default', 'instructions', 'poster', 'content', 'meta'
    ];

    protected $casts = [
        'content' => 'array',
        'meta' => 'array',
        'directions' => 'array',
        'active' => 'boolean',
        'allow_overlap' => 'boolean'
    ];

    public function cerita()
    {
        return $this->belongsTo(Cerita::class, 'cerita_id');
    }
}
