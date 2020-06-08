<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionBanksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('auction_banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('auction_hash');
            $table->integer('bet_sum');
            $table->integer('user_id');
            $table->integer('bank_sum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('auction_banks');
    }
}
