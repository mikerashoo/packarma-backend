<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSolutionBannerIdToBannerClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_clicks', function (Blueprint $table) {
            $table->foreignId('solution_banner_id')->nullable()->references('id')
                ->on('solution_banners')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banner_clicks', function (Blueprint $table) {
            $table->dropForeign(['solution_banner_id']);
            $table->dropColumn('solution_banner_id');
        });
    }
}
