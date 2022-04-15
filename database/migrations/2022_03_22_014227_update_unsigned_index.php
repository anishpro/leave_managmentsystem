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
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->change();
            $table->unsignedBigInteger('position')->change();
            $table->unsignedBigInteger('duty_station')->change();
        });

        // Schema::table('public_holidays', function (Blueprint $table) {
        //   $table->integer('group_id')->change();
        // });

      
        Schema::table('employee_contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->change();
        });
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('emp_id')->change();
            $table->unsignedBigInteger('role_id')->change();
        });
        Schema::table('total_contract_leaves', function (Blueprint $table) {
            $table->unsignedBigInteger('map_contract_leaves_id')->change();
        });
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id')->change();
            $table->unsignedBigInteger('emp_id')->change();
            $table->unsignedBigInteger('leave_type_id')->change();
        });

        Schema::table('travel', function (Blueprint $table) {
            $table->unsignedBigInteger('emp_id')->change();
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

        Schema::table('menu_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->change();
            $table->unsignedBigInteger('menu_id')->change();
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
