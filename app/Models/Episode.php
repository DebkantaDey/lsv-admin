<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    protected $table = 'tbl_episode';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'podcasts_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'portrait_img' => 'string',
        'landscape_img' => 'string',
        'episode_upload_type' => 'string',
        'episode_audio' => 'string',
        'episode_size' => 'string',
        'is_comment' => 'integer',
        'is_download' => 'integer',
        'is_like' => 'integer',
        'total_view' => 'integer',
        'total_like' => 'integer',
        'total_dislike' => 'integer',
        'status' => 'integer',
    ];

    public function Content()
    {
        return $this->belongsTo(Content::class, 'podcasts_id', 'id');
    }
}
