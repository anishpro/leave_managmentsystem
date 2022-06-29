<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_contract_leaves', function (Blueprint $table) {
            $table->foreign('map_contract_leaves_id')->references('id')->on('map_contract_leaves')->onDelete('RESTRICT');
        });
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('RESTRICT');
        });

        Schema::table('travel_details', function (Blueprint $table) {
            $table->foreign('travel_id')->references('id')->on('travel')->onDelete('RESTRICT');
            $table->foreign('deduction_id')->references('id')->on('deductions')->onDelete('RESTRICT')->nullable();
        });

        Schema::table('leave_details', function (Blueprint $table) {
            $table->foreign('application_id')->references('id')->on('leave_applications')->onDelete('RESTRICT');
        });

        Schema::table('request_travel', function (Blueprint $table) {
            $table->foreign('recommenedBy_id')->references('id')->on('employees')->onDelete('RESTRICT');
        });

        Schema::table('request_travel_details', function (Blueprint $table) {
            $table->foreign('travel_request_id')->references('id')->on('request_travel')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
