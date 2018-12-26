<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_scan', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('url');
            $table->boolean('scanning')->default(1);
            $table->date('expire');
            $table->timestamps();
        });
        Schema::create('scan_result', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 30);
            $table->foreign('code')->references('id')->on('web_scan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('ids');
            $table->string('summary');
            $table->string('publish');
            $table->string('severity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_result');
        Schema::dropIfExists('web_scan');
    }
}
