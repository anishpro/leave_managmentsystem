<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_id')->foreign('contract_id')->references('employee_contracts')->on('id')->onDelete('cascade');
            $table->integer('emp_id')->foreign('emp_id')->references('employees')->on('id')->onDelete('cascade');
            $table->integer('leave_type_id')->foreign('leave_type_id')->references('leave_types')->on('id')->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('day_count');
            $table->string('project')->nullable();
            $table->string('address');
            $table->bigInteger('contact');
            $table->float('leave_days');
            $table->string('attachment');
            $table->enum('application_status', [ 1=>'approved',2=>'rejected',3=>'pending',4=>'re-verification']);
            $table->dateTime('approval_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('leave_applications');
    }
}
