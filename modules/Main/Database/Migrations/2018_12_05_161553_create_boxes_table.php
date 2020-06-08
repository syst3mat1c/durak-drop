<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('category_id')->nullable();

            $table->string('name');
            $table->string('description');
            $table->decimal('price', 6, 2);

            $table->string('slug')->unique();

            $table->integer('rarity');
            $table->integer('icon');

            $table->integer('counter');
            $table->integer('counter_two');
            $table->integer('max_counter_two');
            $table->integer('percents');
            $table->integer('two_percents');
            $table->integer('three_percents');

            $table->integer('discount')->default(0);
            $table->decimal('old_price', 6, 2);

            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type')->nullable();

            $table->integer('order');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes');
    }
}
