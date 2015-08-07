<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('activity_type_id');
            $table->integer('practice_id');
            $table->string('campaign_name');
            $table->double('conversions')->nullable()->default(null);
            $table->double('revenue')->nullable()->default(null);
            $table->double('cost');
            $table->longText('description');
			$table->date('created_at');
			$table->date('updated_at');
			//$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('activity');
	}

}
