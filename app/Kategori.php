<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
	public $timestamps = false;
    protected $table = 'kategori';
    protected $fillable = [
        'name'
    ];
}
