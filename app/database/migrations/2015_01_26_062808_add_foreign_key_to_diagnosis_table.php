<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToDiagnosisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('diagnosis', function($table) {
            $table->foreign('company_id')->references('id')->on('practice');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('diagnosis', function($table)
        {
            $table->dropForeign('diagnosis_company_id_foreign');
        });
	}

}
