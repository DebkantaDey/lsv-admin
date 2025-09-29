<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'title' => 'string',
        'message' => 'string',
        'image' => 'string',
        'user_id' => 'integer',
        'from_user_id' => 'integer',
        'content_id' => 'integer',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id',);
    }
}
