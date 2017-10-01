<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableManga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga', function($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('artist')->nullable();
            $table->string('author')->nullable();
            $table->text('genres')->nullable();
            $table->string('released_year')->nullable();
            $table->string('mangafox_url');
            $table->string('mangafox_uid');
            $table->string('mangafox_id');
            $table->text('aliases')->nullable();
            $table->text('overview')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
