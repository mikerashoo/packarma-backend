<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageNameAndProductsToBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->foreignId('app_page_id')->nullable()->references('id')
                ->on('app_pages')->cascadeOnDelete();
        });

        Schema::table('solution_banners', function (Blueprint $table) {
            $table->foreignId('app_page_id')->nullable()->references('id')
                ->on('app_pages')->cascadeOnDelete();
        });

        Schema::create('banner_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->nullable()->references('id')
                ->on('banners')->cascadeOnDelete();

            $table->foreignId('solution_banner_id')->nullable()->references('id')
                ->on('solution_banners')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->references('id')
                ->on('products')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['app_page_id']);
            $table->dropColumn('app_page_id');
        });

        Schema::table('solution_banners', function (Blueprint $table) {
            $table->dropForeign(['app_page_id']);
            $table->dropColumn('app_page_id');
        });

        Schema::dropIfExists('banner_products');
    }
}
