<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'tbl_content';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'channel_id' => 'string',
        'category_id' => 'integer',
        'language_id' => 'integer',
        'artist_id' => 'integer',
        'hashtag_id' => 'string',
        'title' => 'string',
        'description' => 'string',
        'portrait_img' => 'string',
        'landscape_img' => 'string',
        'content_type' => 'integer',
        'content_upload_type' => 'string',
        'content' => 'string',
        'content_size' => 'string',
        'content_duration' => 'integer',
        'is_rent' => 'integer',
        'rent_price' => 'integer',
        'is_comment' => 'integer',
        'is_download' => 'integer',
        'is_like' => 'integer',
        'total_view' => 'integer',
        'total_like' => 'integer',
        'total_dislike' => 'integer',
        'playlist_type' => 'integer',
        'is_admin_added' => 'integer',
        'status' => 'integer',
    ];

    public function channel()
    {
        return $this->belongsTo(User::class, 'channel_id', 'channel_id');
    }
    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
