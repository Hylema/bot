<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_passports', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->integer('number')->unique()->unsigned()->nullable();
            $table->integer('series')->unique()->unsigned()->nullable();
            $table->date('issuance_date')->nullable();
            $table->json('photo_path');
            $table->string('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_passports');
    }
}
