<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->unsignedTinyInteger('provider_type');
            $table->unsignedBigInteger('provider_id');
            $table->string('email')->nullable();

            $table->decimal('money', 8, 2)->default(0.00);
            $table->integer('coins')->default(0.00);
            $table->integer('credits')->default(0.00);

            $table->unsignedBigInteger('rating')->default(0);

            $table->string('avatar')->nullable();

            $table->unsignedInteger('referral_id')->nullable();
            $table->string('referral_key')->unique();
            $table->decimal('referral_earns', 8, 2)->default(0.00);

            $table->boolean('is_admin')->default(false);
            $table->boolean('is_bot')->default(false);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
