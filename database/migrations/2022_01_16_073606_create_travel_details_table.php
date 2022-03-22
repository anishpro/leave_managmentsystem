<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_details', function (Blueprint $table) {
            $table->id();
            $table->integer('travel_id')->foreign('travel_id')->references('travels')->on('id')->onDelete('cascade');
            $table->integer('deduction_id')->foreign('deduction_id')->references('deductions')->on('id')->onDelete('cascade')->nullable();
            $table->string('night_halt');
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('no_of_days');
            $table->float('per_deim_rate');
            $table->float('deduction_amount')->nullable();
            $table->float('total_amount');
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
        Schema::dropIfExists('travel_details');
    }
}
