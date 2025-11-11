<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RuangTeka;

class RuangTekaSession extends Model
{
    use HasFactory;

    protected $table = 'ruang_teka_sessions';

    protected $fillable = [
        'template_id', 'user_id', 'grid_state', 'answers', 'score', 'state'
    ];

    protected $casts = [
        'grid_state' => 'array',
        'answers' => 'array',
        'score' => 'integer'
    ];

    public function template()
    {
        return $this->belongsTo(RuangTeka::class, 'template_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
