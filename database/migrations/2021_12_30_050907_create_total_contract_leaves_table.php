<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalContractLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_contract_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('map_contract_leaves_id')->foreign('map_contract_leaves_id')->references('map_contract_leaves')->on('id')->onDelete('cascade');
            $table->float('total_leaves');
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
        Schema::dropIfExists('total_contract_leaves');
    }
}
