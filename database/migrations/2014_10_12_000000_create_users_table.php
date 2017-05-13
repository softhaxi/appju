<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('name');
            $table->integer('level')->default(0);
            $table->string('email');
            $table->string('password');
            $table->string('device', 36)->nullable();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('mobile');
            $table->string('hash');
            $table->integer('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->unique('name', 'email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
