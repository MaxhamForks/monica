<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateU2fKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            Schema::table('u2f_key', function (Blueprint $table) {
                $table->dropForeign('u2f_key_user_id_foreign');
            });
        } else {
            Schema::table('u2f_key', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
            Schema::table('u2f_key', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        Schema::table('u2f_key', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
