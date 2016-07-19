<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name'); // Premise name
            $table->integer('user_id')->unsigned()->indexed();
            $table->string('power_distributor')->nullable();  // Make it another table.
            $table->enum('type',['Household','Commercial'])->nullable();
            $table->string('slug')->nullable();        
            $table->timestamps();
            // 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('connections');
    }
}
