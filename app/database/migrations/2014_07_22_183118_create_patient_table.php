<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create
        Schema::create('patient', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('referral_source_id')->nullable()->default(null);
            $table->integer('activities_source_id')->nullable()->default(null);
			$table->boolean('is_activities_type')->nullable()->default(null);
            $table->integer('practice_id');
            $table->integer('insurance_id')->nullable()->default(null);
            $table->integer('therapist_id')->nullable()->default(null);
            $table->string('name');
            $table->string('phone');
            $table->integer('how_found_id')->nullable()->default(null);;
            $table->string('reason_no_schedule')->nullable()->default(null);
            $table->boolean('is_scheduled');
			$table->integer('diagnosis_id')->nullable()->default(null);
			$table->boolean('is_doctor_referral')->nullable()->default(null);
            $table->float('value')->default('0');
           // $table->string('address')->nullable()->default(null); // serialized json
			$table->string('address1')->nullable()->default(null);
			$table->string('address2')->nullable()->default(null);
			$table->string('city')->nullable()->default(null);
			$table->string('state')->nullable()->default(null);
			$table->integer('zip')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->date('first_appointment')->nullable()->default(null);
            $table->longText('diag_desc');
            $table->date('date_of_birth')->nullable()->default(null);
            $table->string('sex')->nullable()->default(null);
            $table->string('employer')->nullable()->default(null);
            $table->string('workstatus')->nullable()->default(null);
            $table->string('occupation')->nullable()->default(null);
            $table->string('family_orients')->nullable()->default(null);
            $table->longText('notes');
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
        Schema::drop('patient');
    }

}
