<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditOwnerIdOnCrmnotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `crm_notes` CHANGE COLUMN `owner_id` `owner_id` int(10) UNSIGNED NULL;");
        Schema::table('crm_notes', function($table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('crm_notes', function($table) {
            $table->dropForeign('crm_notes_owner_id_foreign');
        });
        DB::statement("ALTER TABLE `crm_notes` CHANGE COLUMN `owner_id` `owner_id` int(11)  NULL;");
	}

}
