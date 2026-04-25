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
            $table->string('title');                   
            $table->text('description')->nullable();     
            $table->integer('duration')->nullable();     
            $table->date('release_date');                 
            $table->boolean('is_showing')->default(0);    
            $table->string('poster')->nullable();         
            $table->string('director')->nullable();       
            $table->string('cast')->nullable();           
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
