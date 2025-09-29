<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Radio_Content extends Model
{
    use HasFactory;

    protected $table = 'tbl_radio_content';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'radio_id' => 'integer',
        'content_id' => 'integer',
        'status' => 'integer',
    ];

    public function Content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
