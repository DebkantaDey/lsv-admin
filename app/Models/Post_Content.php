<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_Content extends Model
{
    use HasFactory;

    protected $table = 'tbl_post_content';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'post_id' => 'integer',
        'content_type' => 'integer',
        'content_url' => 'string',
        'thumbnail_image' => 'string',
        'status' => 'integer',
    ];
}
