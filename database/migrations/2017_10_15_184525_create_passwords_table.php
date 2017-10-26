<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passwords', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->string('imgurl');
            $table->string('username');
            $table->binary('password');
            $table->binary('iv');

            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')
            ->references('id')
            ->on('companies')
            ->onDelete('cascade');

            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')
            ->references('id')
            ->on('teams')
            ->onDelete('cascade');

            $table->string('url');

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
        Schema::dropIfExists('passwords');
    }
}
