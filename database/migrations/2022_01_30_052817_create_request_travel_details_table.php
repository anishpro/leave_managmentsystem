<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTravelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_travel_details', function (Blueprint $table) {
            $table->id();
            $table->Integer('travel_request_id')->foreign('travel_request_id')->reference('request_travel')->on('id')->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('place_of_halt');
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
        Schema::dropIfExists('request_travel_details');
    }
}
