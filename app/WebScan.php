<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebScan extends Model
{
    protected $table = 'web_scan';
    protected $fillable = [
        'id', 'url', 'scanning', 'expire'
    ];
}
