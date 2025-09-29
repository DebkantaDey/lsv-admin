<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $table = 'tbl_ads';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'user_id' => 'integer',
        'title' => 'string',
        'image' => 'string',
        'video' => 'string',
        'redirect_url' => 'string',
        'budget' => 'integer',
        'status' => 'integer',
        'is_hide' => 'integer',
    ];
}
