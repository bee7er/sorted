<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('start');
            $table->string('end');
            $table->string('mod_check');
            $table->smallInteger('u');
            $table->smallInteger('v');
            $table->smallInteger('w');
            $table->smallInteger('x');
            $table->smallInteger('y');
            $table->smallInteger('z');
            $table->smallInteger('a');
            $table->smallInteger('b');
            $table->smallInteger('c');
            $table->smallInteger('d');
            $table->smallInteger('e');
            $table->smallInteger('f');
            $table->smallInteger('g');
            $table->smallInteger('h');
            $table->smallInteger('exception')->nullable();
            // For updates, we will inactivate a complete set of weights
            $table->timestamp('')->nullable();
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
        Schema::dropIfExists('weights');
    }
}
