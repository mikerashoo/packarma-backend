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
            $table->string('vendor_company_name', 100)->after('vendor_name');
            $table->string('vendor_password')->after('vendor_email');
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
<<<<<<< HEAD
            //
=======
            $table->dropColumn('is_verified');
            $table->dropColumn('fpwd_flag');
            $table->dropColumn('last_login');
            $table->dropColumn('remember_token');
            $table->dropColumn('vendor_company_name');
>>>>>>> maaz
        });
    }
}
