<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('code', 20);
            $table->string('name', 100)->nullable();
            $table->string('address', 100);
            $table->string('address2', 100)->nullable();
            $table->string('address3', 100)->nullable();
            $table->string('rate', 10)->nullable();
            $table->bigInteger('power')->default(0);
            $table->bigInteger('stand_start')->default(0);
            $table->bigInteger('stand_end')->default(0);
            $table->bigInteger('kwh')->default(0);
            $table->float('ptl')->default(0);
            $table->bigInteger('stamp')->default(0);
            $table->float('bank_fee')->default(0);
            $table->float('ppn')->default(0);
            $table->integer('pju')->default(0);
            $table->float('monthly_bill')->default(0);
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
        Schema::drop('customers');
    }
}
