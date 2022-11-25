<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->increments('id');

            $table->string('auth')->nullable()->default(null);
            $table->text('file')->nullable()->default(null);
            $table->tinyInteger('repechaje')->nullable()->default(0);

            $table->integer('winner')->nullable()->default(0);
            $table->integer('points')->nullable()->default(0);

            $table->integer('department')->nullable()->default(null)->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');

            $table->integer('opportunity')->nullable()->default(null)->unsigned();
            $table->foreign('opportunity')->references('id')->on('opportunity')->onDelete('cascade');

            $table->integer('player')->nullable()->default(null)->unsigned();
            $table->foreign('player')->references('id')->on('players')->onDelete('cascade');

            $table->integer('atm')->nullable()->default(null)->unsigned();
            $table->foreign('atm')->references('id')->on('atms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moves');
    }
}
