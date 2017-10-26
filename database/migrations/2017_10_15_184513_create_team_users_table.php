<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

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

            $table->binary('token');
            $table->binary('salt');

            $table->text('role');

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
        Schema::dropIfExists('team_users');
    }
}
