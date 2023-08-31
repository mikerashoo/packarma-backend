<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryUnselectImageColumnToCategoryTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 16-Sept-2022
     * Uses : To add new column tin category table
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('category_unselect_image', 100)->nullable()->after('category_thumb_image');
            $table->string('category_unselect_thumb_image', 100)->nullable()->after('category_unselect_image');
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
            //
        });
    }
}
