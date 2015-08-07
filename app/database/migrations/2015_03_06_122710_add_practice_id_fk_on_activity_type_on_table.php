<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPracticeIdFkOnActivityTypeOnTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('activity_type', function($table) {
            $table->integer('practice_id')->unsigned()->nullable();
            $table->foreign('practice_id')->references('id')->on('practice');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('activity_type', function($table) {
            $table->dropForeign('activity_type_practice_id_foreign');
        });

        Schema::table('activity_type', function($table)
        {
            $table->dropColumn('practice_id');
        });
	}

}
