<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntroToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('=users', function (Blueprint $table) {
            $table->string('userinfo', 140);
            $table->foreignId('userinfo_id')->constrained('userinfo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('=users', function (Blueprint $table) {
            $table->dropColumn('userinfo_id');
        });
    }
}
