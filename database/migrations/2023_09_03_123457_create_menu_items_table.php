<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->comment('See Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemable and Hexatex\LaravelMenu\MenuItem\Contracts\MenuItemParent');

            $table->ulid('id')->index();
            $table->string('display_name');

            $table->nullableUlidMorphs('parent');
            $table->ulidMorphs('menu_itemable');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
