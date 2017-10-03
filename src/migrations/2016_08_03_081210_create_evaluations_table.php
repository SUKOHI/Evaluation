<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function(Blueprint $table)
        {
            $table->increments('id')->comment('ID');
            $table->string('parent')->comment('Model name');
            $table->integer('parent_id')->comment('Model ID');
            $table->integer('type_id')->comment('Evaluation type ID');
            $table->integer('user_id')->unsigned()->comment('User ID');
            $table->string('ip')->nullable()->comment('IP address');
            $table->string('user_agent')->nullable()->comment('User-Agent');
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
        Schema::drop('evaluations');
    }
}
