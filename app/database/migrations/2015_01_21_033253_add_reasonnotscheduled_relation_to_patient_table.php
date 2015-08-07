<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonnotscheduledRelationToPatientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('patient', function($table) {
            $table->integer('reasonnotscheduled_id')->unsigned()->nullable();
            $table->foreign('reasonnotscheduled_id')->references('id')->on('reasons');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('patient', function($table)
        {
            $table->dropForeign('patient_reasonnotscheduled_id_foreign');
            $table->dropColumn('reasonnotscheduled_id');
        });
	}

}
