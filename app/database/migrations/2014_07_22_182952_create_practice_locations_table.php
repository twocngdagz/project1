<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePracticeLocationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create
        Schema::create('practice_locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('practice_id');
            $table->string('name');
            $table->string('phone');
            $table->string('fax');
            $table->string('website');
            $table->string('address');
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
        //drop
        Schema::drop('practice_locations');
    }

}
