<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUniqueIndexOnReferringOfficeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('referring_offices', function($table)
        {
            $table->dropUnique('referring_offices_name_unique');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('referring_offices', function($table)
        {
            $table->unique('name');
        });
	}

}
