<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
            $table->enum('is_verified', ['Y', 'N'])->default('N')->after('status');
            $table->enum('fpwd_flag', ['Y', 'N'])->default('N')->after('is_verified');
            $table->datetime('last_login')->nullable()->after('fpwd_flag');
            $table->longText('remember_token')->nullable()->after('last_login');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
}
