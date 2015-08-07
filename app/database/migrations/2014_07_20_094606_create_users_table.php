<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Users table 
        Schema::create('users', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('practice_id')->nullable()->default(null);
            $table->string('position')->default('');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 64);
            $table->string('confirmation_code')->default('');
            $table->rememberToken();
            $table->string('payment_token')->default('');
            $table->enum('role', array('staff', 'manager', 'admin'));
            $table->boolean('is_active')->default(true);
            $table->boolean('is_expired')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->binary('columns_patient');
            $table->binary('filters_patient');
            $table->timestamp('lastpayment');
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
        // Drop  Users table
        Schema::drop('users');
    }

}
