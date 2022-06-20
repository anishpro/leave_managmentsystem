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
        if (!Schema::hasTable('group_holidays')) {
            Schema::create('group_holidays', function (Blueprint $table) {
                // unsigned is needed for foreign key
                $table->unsignedBigInteger('group_id');
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('restrict');
                $table->unsignedBigInteger('holiday_id');
                $table->foreign('holiday_id')->references('id')->on('public_holidays')->onDelete('restrict');
                $table->primary(['group_id', 'holiday_id']);
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
