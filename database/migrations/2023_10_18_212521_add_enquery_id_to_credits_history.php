<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnqueryIdToCreditsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_credit_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('enquery_id')->default(0);
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
            $table->dropColumn('id');
            $table->dropColumn('enquery_id');
        });
    }
}
