<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmCommentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create
        Schema::create('crm_comments', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('owner_id');
            $table->integer('practice_id');
            $table->integer('task_id');
            $table->string('comment');
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
        // drop
        Schema::drop('crm_comments');
    }

}
