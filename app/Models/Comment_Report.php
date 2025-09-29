<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment_Report extends Model
{
    use HasFactory;

    protected $table = 'tbl_comment_report';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'report_user_id' => 'integer',
        'comment_id' => 'integer',
        'message' => 'string',
        'status' => 'integer',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function report_user()
    {
        return $this->belongsTo(User::class, 'report_user_id');
    }
}
