<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmNotesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create
        Schema::create('crm_notes', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('owner_id');
            $table->integer('practice_id');
            $table->longText('description');
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
        Schema::drop('crm_notes');
    }

}
