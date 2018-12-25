<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul');
            $table->string('isi');
            $table->string('foto');
            $table->timestamps();
        });
        Schema::create('kategori', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('kategori_news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_news')->unsigned();
            $table->foreign('id_news')->references('id')->on('news')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('id_kategori')->unsigned();
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kategori_news');
        Schema::drop('news');
        Schema::drop('kategori');
    }
}
