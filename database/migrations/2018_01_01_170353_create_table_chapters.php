<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChapters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function($table) {
            $table->increments('id');
            $table->string('number');
            $table->string('volume')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('scans')->nullable();
            $table->integer('manga_id')->unsigned();
            $table->timestamps();
            $table->timestamp('released_at')->nullable();
            $table->softDeletes();
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
        //
    }
}
