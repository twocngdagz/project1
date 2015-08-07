<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysOnPatientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `practice_id` `practice_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `activities_source_id` `activity_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `referral_source_id` `referral_source_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `insurance_id` `insurance_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `therapist_id` `therapist_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `how_found_id` `how_found_id` int(10) UNSIGNED NULL;");
        Schema::table('patient', function($table) {
            $table->foreign('practice_id')->references('id')->on('practice');
            $table->foreign('activity_id')->references('id')->on('activity');
            $table->foreign('referral_source_id')->references('id')->on('referral_source');
            $table->foreign('insurance_id')->references('id')->on('insurance');
            $table->foreign('therapist_id')->references('id')->on('therapists');
            $table->foreign('how_found_id')->references('id')->on('howfound');
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
            $table->dropForeign('patient_practice_id_foreign');
        });

        Schema::table('patient', function($table) {
            $table->dropForeign('patient_activity_id_foreign');
        });

        Schema::table('patient', function($table) {
            $table->dropForeign('patient_referral_source_id_foreign');
        });

        Schema::table('patient', function($table) {
            $table->dropForeign('patient_insurance_id_foreign');
        });

        Schema::table('patient', function($table) {
            $table->dropForeign('patient_therapist_id_foreign');
        });

        Schema::table('patient', function($table) {
            $table->dropForeign('patient_how_found_id_foreign');
        });

        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `practice_id` `practice_id` int(11) NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `activity_id` `activities_source_id` int(11) NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `referral_source_id` `referral_source_id` int(11) NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `insurance_id` `insurance_id` int(11) NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `therapist_id` `therapist_id` int(11) NULL;");
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `how_found_id` `how_found_id` int(11) NULL;");
	}

}
