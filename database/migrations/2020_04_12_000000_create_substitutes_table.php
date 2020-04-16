<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubstitutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('substitutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('original_sort_code');
            $table->string('substitute_sort_code');
            // For updates, we will inactivate a complete set of weights
            $table->timestamp('inactivated_at')->nullable();
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
        Schema::dropIfExists('substitutes');
    }
}
