<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('connection_id')->unsigned()->indexed(); // Belongs to a connection
            $table->string('name')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('start_reading')->nullable();
            $table->integer('end_reading')->nullable();
            $table->smallInteger('consumption')->nullable();
            $table->integer('energy_charges')->nullable(); //Amount on Energy charges 
            $table->enum('status',['Current','Previous'])->nullable();
            $table->enum('type',['Reading Cycle','Bill'])->nullabel();
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
        Schema::drop('bills');
    }
}
