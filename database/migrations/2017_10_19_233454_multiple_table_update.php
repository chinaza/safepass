<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MultipleTableUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('pkeys', function (Blueprint $table) {
        $table->string('public', 4096);
        $table->renameColumn('pKey', 'private');
      });

      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('pkey');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
