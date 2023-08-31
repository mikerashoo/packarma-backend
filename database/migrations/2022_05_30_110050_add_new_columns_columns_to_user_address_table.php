<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsColumnsToUserAddressTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 30/05/2022
     * Uses : Adding new columns to user address table
     * 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('flat', 255)->nullable()->after('pincode');
            $table->string('area', 255)->nullable()->after('flat');
            $table->string('land_mark', 255)->nullable()->after('area');
            $table->string('city_name', 255)->nullable()->after('land_mark');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('flat');
            $table->dropColumn('area');
            $table->dropColumn('land_mark');
            $table->dropColumn('city_name');
        });
    }
}
