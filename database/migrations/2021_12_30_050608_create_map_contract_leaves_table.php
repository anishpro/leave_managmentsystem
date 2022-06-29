<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapContractLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_contract_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_type_id')->foreign('leave_type_id')->references('leave_types')->on('id')->onDelete('cascade');
            $table->float('contract_month');
            $table->float('leave_days');
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
        Schema::dropIfExists('map_contract_leaves');
    }
}
