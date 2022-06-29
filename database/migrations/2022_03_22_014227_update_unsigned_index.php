<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUnsignedIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_contract_leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('map_contract_leaves_id')->change();
        });
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('leave_type_id')->change();
        });

        Schema::table('travel_details', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_id')->change();
            $table->unsignedBigInteger('deduction_id')->change();
        });
        Schema::table('leave_details', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->change();
        });

        Schema::table('request_travel', function (Blueprint $table) {
            $table->unsignedBigInteger('emp_id')->change();
            $table->unsignedBigInteger('recommenedBy_id')->change();
        });

        Schema::table('request_travel_details', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_request_id')->change();
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
