<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSpecialDateIdFromReminders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['special_date_id', 'last_triggered', 'next_expected_date'] as $field) {
            Schema::table('reminders', fn(Blueprint $table) => $table->dropColumn($field));
        }

        Schema::table('special_dates', function (Blueprint $table) {
            $table->dropColumn('reminder_id');
        });
    }
}
