<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreetLightingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('street_lightings', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('mobile_id', 36)->nullable();
            $table->string('customer_id', 36)->nullable();
            $table->string('customer_name', 100)->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('power')->nullable();
            $table->string('rate')->nullable();
            $table->integer('number_of_lamp')->default(0);
            $table->float('latitude')->default(0);
            $table->float('longitude')->default(0);
            $table->string('geolocation')->nullable();
            $table->string('remark')->nullable();
            $table->string('pole')->nullable();
            $table->string('name')->nullable();
            $table->integer('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('street_lightings');
    }
}
