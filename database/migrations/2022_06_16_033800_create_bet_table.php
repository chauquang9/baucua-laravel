<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('baucua_id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('user_id');
            $table->float('money_bet');
            $table->timestamps();

            $table->foreign('baucua_id')->references('id')->on('baucua');
            $table->foreign('game_id')->references('id')->on('game');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet');
    }
}
