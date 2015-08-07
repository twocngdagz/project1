<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFieldsOnPatientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('patient', function($table) {
            $table->dropForeign('patient_activity_id_foreign');
            $table->dropForeign('patient_diagnosis_id_foreign');
            $table->dropForeign('patient_insurance_id_foreign');
            $table->dropForeign('patient_reasonnotscheduled_id_foreign');
            $table->dropForeign('patient_referral_source_id_foreign');
            $table->dropForeign('patient_therapist_id_foreign');
        });

        Schema::table('patient', function($table)
        {
            $table->dropColumn('referral_source_id');
            $table->dropColumn('activity_id');
            $table->dropColumn('insurance_id');
            $table->dropColumn('therapist_id');
            $table->dropColumn('diagnosis_id');
            $table->dropColumn('reasonnotscheduled_id');
            $table->dropColumn('is_scheduled');
            $table->dropColumn('first_appointment');
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
            $table->integer('diagnosis_id')->unsigned()->nullable();
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis');
            $table->integer('referral_source_id')->unsigned()->nullable();
            $table->foreign('referral_source_id')->references('id')->on('referral_source');
            $table->integer('activity_id')->unsigned()->nullable();
            $table->foreign('activity_id')->references('id')->on('activity');
            $table->integer('reasonnotscheduled_id')->unsigned()->nullable();
            $table->foreign('reasonnotscheduled_id')->references('id')->on('reasons');
            $table->integer('insurance_id')->unsigned()->nullable();
            $table->foreign('insurance_id')->references('id')->on('insurance');
            $table->integer('therapist_id')->unsigned()->nullable();
            $table->foreign('therapist_id')->references('id')->on('therapists');
            $table->dateTime('first_appointment')->nullable();
            $table->boolean('is_scheduled')->default(false);
        });
	}

}
