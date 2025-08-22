<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ApplyAffiliate extends Model
{
    // use HasFactory;

    protected $fillable = [
        'brand_name',
        'name',
        'email',
        'phone',
        'audience',
        'web_link',
        'referring',
        'referring_other',
        'audience_benefit',
        'audience_promote',
        'need_help',
        'url',
        'ip_address',
        'city',
        'country',
        'internet_connection',
        'zipcode',
        'region'
    ];
}


