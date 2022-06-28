<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GroupHoliday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_public_holiday', function (Blueprint $table) {
            // unsigned is needed for foreign key
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('public_holiday_id')->constrained('public_holidays')->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_holiday');
    }
}
