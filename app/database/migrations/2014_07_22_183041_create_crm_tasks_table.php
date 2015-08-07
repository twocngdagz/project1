<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmTasksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create
        Schema::create('crm_tasks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('owner_id');
            $table->integer('practice_id');
            $table->integer('assigned_to');
            $table->integer('updater_id');
            $table->string('title');
            $table->string('description');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completion_date');
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
        Schema::drop('crm_tasks');
    }

}
