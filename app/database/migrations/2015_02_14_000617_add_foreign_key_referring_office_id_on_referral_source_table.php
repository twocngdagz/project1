<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferringOfficeIdOnReferralSourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('referral_source', function($table) {
            $table->integer('referring_office_id')->unsigned()->nullable();
            $table->foreign('referring_office_id')->references('id')->on('referring_offices');
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
            $table->dropForeign('referral_source_referring_office_id_foreign');
        });

        Schema::table('referral_source', function($table)
        {
            $table->dropColumn('referring_office_id');
        });
	}

}
