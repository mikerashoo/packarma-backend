<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnteredShelfLifeAndEnterdShelfLifeUnitToCustomerEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->integer('entered_shelf_life')->default(0)->after('shelf_life');
            $table->string('entered_shelf_life_unit', 255)->nullable()->after('entered_shelf_life');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            //
        });
    }
}
