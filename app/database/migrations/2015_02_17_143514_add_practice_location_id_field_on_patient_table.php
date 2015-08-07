<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPracticeLocationIdFieldOnPatientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('patient', function($table) {
            $table->integer('practice_location_id')->unsigned()->nullable();
            $table->foreign('practice_location_id')->references('id')->on('practice_locations');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('patient', function($table) {
            $table->dropForeign('patient_practice_location_id_foreign');
        });

        Schema::table('patient', function($table)
        {
            $table->dropColumn('practice_location_id');
        });
	}

}
