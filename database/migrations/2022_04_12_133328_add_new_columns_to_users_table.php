<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('is_verified', ['Y', 'N'])->default('N')->after('remember_token');
            $table->string('visiting_card_front')->nullable()->after('is_verified');
            $table->string('visiting_card_back')->nullable()->after('visiting_card_front');
            $table->enum('fpwd_flag', ['Y', 'N'])->default('N')->after('visiting_card_back');
            $table->datetime('last_login')->nullable()->after('fpwd_flag');
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
            $table->dropColumn('is_verified');
            $table->dropColumn('visiting_card_front');
            $table->dropColumn('visiting_card_back');
            $table->dropColumn('fpwd_flag');
            $table->dropColumn('last_login');
        });
    }
}
