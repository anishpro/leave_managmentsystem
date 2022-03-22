<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code')->unique();
            $table->integer('role_id')->foreign('role_id')->references('roles')->on('id')->onDelete('cascade');
            $table->Integer('group_id')->foreign('group_id')->references('groups')->on('id')->onDelete('cascade');
            $table->string('name');
            $table->Integer('position')->foreign('position')->references('employee_positions')->on('id')->onDelete('cascade');
            $table->Integer('duty_station')->foreign('duty_station')->references('duty_stations')->on('id')->onDelete('cascade');
            $table->bigInteger('phone');
            $table->string('email')->unique();
            $table->text('address')->nullable();
            $table->text('signature')->nullable();
            $table->text('profile')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
