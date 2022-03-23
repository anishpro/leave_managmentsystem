<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if (!Schema::hasTable('menus')) {
          Schema::create('menus', function (Blueprint $table) {
              $table->id();
              $table->unsignedBigInteger('parent_id');
              $table->String('menu_name');
              $table->String('menu_url');
              $table->unsignedBigInteger('module_id');
              $table->Integer('status');
              $table->timestamps();
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
        Schema::dropIfExists('menus');
    }
}
