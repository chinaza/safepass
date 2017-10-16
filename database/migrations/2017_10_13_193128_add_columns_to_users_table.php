<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->renameColumn('name', 'fullName');
          $table->string('avatar')->after('email')->nullable();
          $table->string('company')->after('avatar')->nullable();
          $table->string('position')->after('company')->nullable();
          $table->string('skills')->after('position')->nullable();
          $table->string('key')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
