<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class KategoriNews extends Model
{
	public $timestamps = false;
    protected $table = 'kategori_news';
    protected $fillable = [
        'id_news', 'id_kategori'
    ];
}
