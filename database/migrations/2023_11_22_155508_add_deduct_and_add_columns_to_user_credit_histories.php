<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeductAndAddColumnsToUserCreditHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_credit_histories', function (Blueprint $table) {
            $table->integer('deduct')->nullable();
            $table->integer('add')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_credit_histories', function (Blueprint $table) {
            $table->dropColumn('deduct');
            $table->dropColumn('add');
        });
    }
}
