<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_Comment extends Model
{
    use HasFactory;

    protected $table = 'tbl_post_comment';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'comment__id' => 'integer',
        'post_id' => 'integer',
        'user_id' => 'integer',
        'comment' => 'string',
        'status' => 'integer',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
