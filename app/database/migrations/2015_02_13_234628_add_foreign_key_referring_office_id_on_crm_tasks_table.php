<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyReferringOfficeIdOnCrmTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('crm_tasks', function($table) {
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
        Schema::table('crm_tasks', function($table) {
            $table->dropForeign('crm_tasks_referring_office_id_foreign');
        });

        Schema::table('crm_tasks', function($table)
        {
            $table->dropColumn('referring_office_id');
        });
	}

}
