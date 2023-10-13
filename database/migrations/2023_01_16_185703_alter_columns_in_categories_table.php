<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsInCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('category_unselect_image');
            $table->dropColumn('category_unselect_thumb_image');
           $table->string('category_unselected_image', 100)->nullable()->after('category_thumb_image');
            $table->string('category_unselected_thumb_image', 100)->nullable()->after('category_unselect_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('category_unselected_image');
            $table->dropColumn('category_unselected_thumb_image');
        });
    }
}
