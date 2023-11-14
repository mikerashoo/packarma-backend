<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->nullable()->references('id')
                ->on('banners')->cascadeOnDelete();
            $table->foreignId('solution_banner_id')->nullable()->references('id')
                ->on('solution_banners')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->references('id')
                ->on('users')->cascadeOnDelete();
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
        Schema::dropIfExists('banner_views');
    }
}
