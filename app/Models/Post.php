<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'tbl_post';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'channel_id' => 'string',
        'category_id' => 'integer',
        'hashtag_id' => 'string',
        'title' => 'string',
        'descripation' => 'string',
        'is_comment' => 'integer',
        'view' => 'integer',
        'status' => 'integer',
    ];

    public function channel()
    {
        return $this->belongsTo(User::class, 'channel_id', 'channel_id');
    }
}
