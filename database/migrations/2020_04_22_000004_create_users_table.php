<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   Schema::defaultStringLength(300);
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->string('email');
            $table->string('nombre')->nullable()->default(null);
            $table->string('codigo')->nullable()->default(null);
            $table->text('picture')->nullable()->default(null);
            $table->timestamp('last_conection')->useCurrent();
            $table->text('facebook_id')->nullable()->default(null);
            $table->text('google_id')->nullable()->default(null);
            $table->text('google_token')->nullable()->default(null);
            $table->text('google_idToken')->nullable()->default(null);
            $table->text('token')->nullable()->default(null);
            $table->integer('estado')->nullable()->default(1);

            $table->integer('rol')->nullable()->default(null)->unsigned();
            $table->foreign('rol')->references('id')->on('roles')->onDelete('cascade');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
