<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_Report extends Model
{
    use HasFactory;

    protected $table = 'tbl_post_report';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'report_user_id' => 'integer',
        'post_id' => 'integer',
        'message' => 'string',
        'status' => 'integer',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function report_user()
    {
        return $this->belongsTo(User::class, 'report_user_id');
    }
}
