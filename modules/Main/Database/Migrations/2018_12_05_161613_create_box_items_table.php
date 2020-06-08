<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxItemsTable extends Migration
{
    public function up()
    {
        Schema::create('box_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('box_id');
            $table->decimal('price', 12, 2);
            $table->integer('amount');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('rarity');
            $table->unsignedTinyInteger('wealth');
            $table->boolean('is_gaming')->default(true);

            $table->timestamps();

            $table->foreign('box_id')->references('id')->on('boxes')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('box_items');
    }
}
