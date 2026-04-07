<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');                    // 电影标题
            $table->text('description')->nullable();     // 电影描述
            $table->integer('duration')->nullable();     // 时长（分钟）
            $table->date('release_date');                 // 上映日期
            $table->boolean('is_showing')->default(0);    // 是否正在上映
            $table->string('poster')->nullable();         // 海报图片
            $table->string('director')->nullable();       // 导演
            $table->string('cast')->nullable();           // 演员
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}