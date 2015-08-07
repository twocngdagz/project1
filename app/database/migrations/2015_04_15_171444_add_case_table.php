<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cases', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('patient_id')->unsigned()->nullable();
            $table->foreign('patient_id')->references('id')->on('patient');
            $table->integer('diagnosis_id')->unsigned()->nullable();
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis');
            $table->integer('referralsource_id')->unsigned()->nullable();
            $table->foreign('referralsource_id')->references('id')->on('referral_source');
            $table->integer('activity_id')->unsigned()->nullable();
            $table->foreign('activity_id')->references('id')->on('activity');
            $table->integer('reasonnotscheduled_id')->unsigned()->nullable();
            $table->foreign('reasonnotscheduled_id')->references('id')->on('reasons');
            $table->integer('insurance_id')->unsigned()->nullable();
            $table->foreign('insurance_id')->references('id')->on('insurance');
            $table->integer('therapist_id')->unsigned()->nullable();
            $table->foreign('therapist_id')->references('id')->on('therapists');
            $table->dateTime('first_appointment')->nullable();
            $table->dateTime('free_evaluation')->nullable();
            $table->boolean('is_scheduled')->default(false);
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
        Schema::drop('cases');
	}

}
