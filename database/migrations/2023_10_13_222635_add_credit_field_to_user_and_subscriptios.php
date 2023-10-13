<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditFieldToUserAndSubscriptios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('current_credit_amount')->default(0);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('credit_amount')->default(0);
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
            $table->dropColumn('current_credit_amount');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('credit_amount');
        });
    }
}
