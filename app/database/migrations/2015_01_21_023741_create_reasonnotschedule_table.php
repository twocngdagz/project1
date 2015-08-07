<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReasonnotscheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('reasons', function($table) {
            $table->increments('id');

            $table->string('description');
            $table->integer('practice_id')->unsigned()->nullable();
            $table->foreign('practice_id')->references('id')->on('practice');
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
        Schema::table('reasons', function($table)
        {
            $table->dropForeign('reasons_practice_id_foreign');
        });
		Schema::drop('reasons');
	}

}
