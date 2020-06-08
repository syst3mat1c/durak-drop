<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChancesTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('chances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('iteration')->default(0);
            $table->unsignedTinyInteger('status');
            $table->json('json');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('chances', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('chances');
    }
}
