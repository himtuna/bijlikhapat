<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bill_id')->unsigned()->indexed();
            $table->integer('current_reading')->nullable(); //Current meter reading
            $table->integer('current_consumption')->nullable(); //Current meter reading
            $table->integer('current_energy_charges')->nullable(); //Current energy charges            
            $table->string('slug')->nullable();
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
        Schema::drop('readings');
    }
}
