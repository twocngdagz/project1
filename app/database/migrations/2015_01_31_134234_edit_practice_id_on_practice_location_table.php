<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPracticeIdOnPracticeLocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `practice_locations` CHANGE COLUMN `practice_id` `practice_id` int(10) UNSIGNED NULL;");
        Schema::table('practice_locations', function($table) {
            $table->foreign('practice_id')->references('id')->on('practice');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('practice_locations', function($table) {
            $table->dropForeign('practice_locations_practice_id_foreign');
        });
        DB::statement("ALTER TABLE `practice_locations` CHANGE COLUMN `practice_id` `practice_id` int(11)  NULL;");
	}

}
