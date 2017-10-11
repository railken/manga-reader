<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLibraries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libraries', function($table) {
            $table->integer('user_id')->unsigned();
            $table->integer('manga_id')->unsigned();
            $table->primary(['user_id', 'manga_id']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('manga_id')->references('id')->on('manga');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_manga');
    }
}
