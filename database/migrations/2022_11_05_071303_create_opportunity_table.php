<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpportunityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunity', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('code')->nullable()->default(null);
            $table->integer('random_position')->nullable()->default(null);
            $table->integer('points')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->tinyInteger('avaliable')->nullable()->default(1);
            $table->tinyInteger('repechaje')->nullable()->default(0);

            $table->integer('reward')->nullable()->default(null)->unsigned();
            $table->foreign('reward')->references('id')->on('reward')->onDelete('cascade');

            $table->integer('department')->nullable()->default(null)->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('opportunity');
    }
}
