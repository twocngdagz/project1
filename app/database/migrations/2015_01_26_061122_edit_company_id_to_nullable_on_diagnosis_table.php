<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditCompanyIdToNullableOnDiagnosisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `diagnosis` CHANGE COLUMN `company_id` `company_id` int(10) UNSIGNED NULL;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("ALTER TABLE `diagnosis` CHANGE COLUMN `company_id` `company_id` int(10);");
	}

}
