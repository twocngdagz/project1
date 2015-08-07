<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiagnosisForeignKeyOnPatientTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `diagnosis_id` `diagnosis_id` int(10) UNSIGNED NULL;");
        Schema::table('patient', function($table) {
            $table->foreign('diagnosis_id')->references('id')->on('diagnosis');
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
            $table->dropForeign('patient_diagnosis_id_foreign');
        });

        DB::statement("ALTER TABLE `patient` CHANGE COLUMN `diagnosis_id` `diagnosis_id` int(11) NULL;");
	}

}
