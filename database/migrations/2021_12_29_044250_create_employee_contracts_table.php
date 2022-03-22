<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->foreign('employee_id')->references('employees')->on('id')->onDelete('cascade');
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->integer('no_of_months');
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
        Schema::dropIfExists('employee_contracts');
    }
}
