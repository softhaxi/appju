<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreetLightingLampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('street_lighting_lamps', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('street_lighting_id', 36)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->bigInteger('power')->default(0);
            $table->float('latitude')->default(0);
            $table->float('longitude')->default(0);
            $table->string('geolocation')->nullable();
            $table->string('remark')->nullable();
            $table->integer('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('street_lighting_lamps');
    }
}
