<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('flight_id');
            $table->string('dom_int');
            $table->string('schedule_time');
            $table->string('arr_dep');
            $table->string('airport_code');
            $table->string('airline');
            $table->string('via_airport')->nullable(); // komma separert mellomlandinger, airport_code identifier
            $table->string('check_in')->nullable();
            $table->string('gate')->nullable();
            $table->string('status_code')->nullable();
            $table->string('status_time')->nullable();
            $table->string('belt_number')->nullable();
            $table->string('delayed')->nullable();
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
        Schema::dropIfExists('flights');
    }
}
