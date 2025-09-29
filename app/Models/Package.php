<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'tbl_package';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'price' => 'integer',
        'image' => 'string',
        'no_of_device' => 'integer',
        'ads_free' => 'integer',
        'download' => 'integer',
        'background_play' => 'integer',
        'size_of_data_upload' => 'string',
        'verifly_artist' => 'integer',
        'verifly_account' => 'integer',
        'time' => 'string',
        'type' => 'string',
        'android_product_package' => 'string',
        'ios_product_package' => 'string',
        'web_product_package' => 'string',
        'status' => 'integer',
    ];
}
