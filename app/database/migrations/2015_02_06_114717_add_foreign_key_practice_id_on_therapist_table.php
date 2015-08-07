<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyPracticeIdOnTherapistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `therapists` CHANGE COLUMN `practice_id` `practice_id` int(10) UNSIGNED NULL;");
        Schema::table('therapists', function($table) {
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
        Schema::table('therapists', function($table) {
            $table->dropForeign('therapists_practice_id_foreign');
        });

        DB::statement("ALTER TABLE `therapists` CHANGE COLUMN `practice_id` `practice_id` int(11) NULL;");
	}

}
