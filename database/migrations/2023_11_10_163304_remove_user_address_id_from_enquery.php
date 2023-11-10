<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserAddressIdFromEnquery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_enquiries', function (Blueprint $table) {
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');
            $table->dropColumn('city_name');
            $table->dropColumn('address');
            $table->dropColumn('pincode');
            $table->dropColumn('flat');

            $table->dropColumn('land_mark');
            $table->dropColumn('user_address_id');
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
            $table->string('city_name')->nullable()->after('city_id');
            $table->string('flat')->nullable()->after('city_name');
            $table->string('area')->nullable()->after('flat');
            $table->string('land_mark')->nullable()->after('area');
            $table->longText('address')->nullable();
            $table->integer('country_id')->default(1);
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0);
            $table->integer('pincode')->default(0);
            $table->integer('user_address_id')->default(0);
        });
    }
}
