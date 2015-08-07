<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferringOfficeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('referring_offices', function($table) {
            $table->increments('id');
            $table->integer('practice_id')->unsigned()->nullable();
            $table->foreign('practice_id')->references('id')->on('practice');
            $table->string('name')->unique();
            $table->string('phone')->nullable()->default(NULL);;
            $table->string('fax')->nullable()->default(NULL);;
            $table->string('website')->nullable()->default(NULL);;
            $table->string('address')->nullable()->default(NULL);;
            $table->timestamps();
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
            $table->dropForeign('referring_offices_practice_id_foreign');
        });
        Schema::drop('referring_offices');
	}

}
