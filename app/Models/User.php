<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_user';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'channel_id' => 'string',
        'channel_name' => 'string',
        'full_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'country_code' => 'string',
        'mobile_number' => 'string',
        'country_name' => 'string',
        'image' => 'string',
        'cover_img' => 'string',
        'type' => 'integer',
        'is_artist' => 'integer',
        'description' => 'string',
        'device_type' => 'integer',
        'device_token' => 'string',
        'website' => 'string',
        'facebook_url' => 'string',
        'instagram_url' => 'string',
        'twitter_url' => 'string',
        'wallet_balance' => 'integer',
        'wallet_earning' => 'integer',
        'bank_name' => 'string',
        'bank_code' => 'string',
        'bank_address' => 'string',
        'ifsc_no' => 'string',
        'account_no' => 'string',
        'id_proof' => 'string',
        'address' => 'string',
        'city' => 'string',
        'state' => 'string',
        'country' => 'string',
        'pincode' => 'integer',
        'user_penal_status' => 'integer',
        'status' => 'integer',
    ];
}
