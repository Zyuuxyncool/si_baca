<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cerita;
class RuangTeka extends Model
{
    use HasFactory;

    protected $table = 'ruang_teka';
    // RuangTeka now represents the TEMPLATE table
    protected $fillable = [
        'cerita_id', 'title', 'slug', 'active', 'difficulty', 'time_limit', 'points_default', 'instructions', 'poster', 'content', 'meta', 'grid_rows', 'grid_cols', 'grid', 'clues'
    ];

    protected $casts = [
        'content' => 'array',
        'meta' => 'array',
        'grid' => 'array',
        'clues' => 'array',
        'active' => 'boolean'
    ];

    public function cerita()
    {
        return $this->belongsTo(Cerita::class, 'cerita_id');
    }
}
