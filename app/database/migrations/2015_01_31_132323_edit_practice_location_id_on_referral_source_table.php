<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditPracticeLocationIdOnReferralSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `referral_source` CHANGE COLUMN `practice_location_id` `practice_location_id` int(10) UNSIGNED NULL;");
        Schema::table('referral_source', function($table) {
            $table->foreign('practice_location_id')->references('id')->on('practice_locations');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('referral_source', function($table) {
            $table->dropForeign('referral_source_practice_location_id_foreign');
        });
        DB::statement("ALTER TABLE `referral_source` CHANGE COLUMN `practice_location_id` `practice_location_id` int(11)  NULL;");
	}

}
