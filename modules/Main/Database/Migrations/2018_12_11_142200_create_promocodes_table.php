<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->float('percent');
            $table->unsignedTinyInteger('attempts')->nullable();
            $table->decimal('min_amount',8, 2);
            $table->unsignedTinyInteger('type');
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
