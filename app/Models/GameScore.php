<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameScore extends Model
{
    protected $table = 'game_scores';

    protected $fillable = [
        'user_id',
        'cerita_id',
        'game_template_id',
        'game_template_id2',
        'score',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function cerita() { return $this->belongsTo(Cerita::class); }
}