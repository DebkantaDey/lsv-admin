<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interests_Hashtag extends Model
{
    use HasFactory;

    protected $table = 'tbl_interests_hashtag';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'hashtag_id' => 'integer',
        'count' => 'integer',
    ];
}
