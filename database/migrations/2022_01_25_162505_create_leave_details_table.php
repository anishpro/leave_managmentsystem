<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_details', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id')->foreign('application_id')->references('leave_applications')->on('id')->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->float('no_of_days');
            $table->string('leave_shift');
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
        Schema::dropIfExists('leave_details');
    }
}
