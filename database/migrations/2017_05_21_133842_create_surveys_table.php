<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->string('id', 36);
            $table->string('class')->nullable();
            $table->integer('level')->nullable();
            $table->string('parent_id')->nullable();
            $table->string('action', 20)->default('create');
            $table->string('url')->nullable();
            $table->string('surveyable_id', 36);
            $table->string('surveyable_type');
            $table->integer('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('id');
            $table->foreign('parent_id')->reference('id')->on('surveys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surveys');
    }
}
