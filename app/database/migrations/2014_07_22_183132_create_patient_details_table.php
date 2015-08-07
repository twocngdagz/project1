<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //create
        Schema::create('patient_details', function(Blueprint $table)
        {
            $table->increments('id');
            //~ $table->integer('patient_id');
            //~ $table->string('name'); // string
            //~ $table->string('insurance_id'); // id from insurance table
            //~ $table->string('therapist'); // terapevti from db ?
            //~ $table->string('location'); //practice locations?
            //~ $table->string('address'); // 
            //~ $table->string('phone');
            //~ $table->string('email');
            //~ $table->string('referral_source_id');
            //~ $table->string('howfindus');
            //~ $table->string('is_appointment_scheduled');
            //~ $table->string('reason_not_scheduled');
            //~ $table->string('first_appointment_attended'); 
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
            //~ $table->string('detail1');
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
        Schema::drop('patient_details');
    }

}
