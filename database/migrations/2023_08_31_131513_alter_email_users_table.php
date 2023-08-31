<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeliveryStatusColumnCommentToOrderTable extends Migration
{
    /**
     * @author Mohammed Taqi Syed <mohammed.s@mypcot.com>
     * Created on : 31/08/2023
     * Uses : Removed Unique Check From Email Fields
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
