<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestMenuItemablesTable extends Migration
{
    public function up()
    {
        Schema::create('test_menu_itemables', function (Blueprint $table) {
            $table->ulid('id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_menu_itemables');
    }
}
