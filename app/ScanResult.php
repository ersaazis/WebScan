<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
	public $timestamps = false;
    protected $table = 'scan_result';
    protected $fillable = [
        'code', 'name', 'ids', 'summary', 'publish', 'severity'
    ];
}
