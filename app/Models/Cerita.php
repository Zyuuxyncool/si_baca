<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cerita extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cerita';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'photo',
        'video',
        'deskripsi',
        'video_processing',
        'video_processed_at',
    ];

    protected $casts = [
        'video_processing' => 'boolean',
        'video_processed_at' => 'datetime',
    ];


    /**
     * Optional: accessors/mutators or relations can be added here.
     */
}
