<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPracticeIdAndActivityTypeIdOnActivityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `activity` CHANGE COLUMN `practice_id` `practice_id` int(10) UNSIGNED NULL;");
        DB::statement("ALTER TABLE `activity` CHANGE COLUMN `activity_type_id` `activity_type_id` int(10) UNSIGNED NULL;");
        Schema::table('activity', function($table) {
            $table->foreign('practice_id')->references('id')->on('practice');
            $table->foreign('activity_type_id')->references('id')->on('activity_type');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('activity', function($table) {
            $table->dropForeign('activity_practice_id_foreign');
        });

        Schema::table('activity', function($table) {
            $table->dropForeign('activity_activity_type_id_foreign');
        });
        DB::statement("ALTER TABLE `activity` CHANGE COLUMN `practice_id` `practice_id` int(11)  NULL;");
        DB::statement("ALTER TABLE `activity` CHANGE COLUMN `activity_type_id` `activity_type_id` int(11)  NULL;");
	}

}
