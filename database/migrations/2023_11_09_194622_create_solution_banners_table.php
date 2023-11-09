<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solution_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('description')->nullable();
            $table->string('banner_image', 100)->nullable();
            $table->string('banner_thumb_image', 100)->nullable();
            $table->string('type', 255)->default('customer')->comment('customer|vendor');
            $table->string('seo_url', 255)->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->enum('status', [1, 0])->default(1);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('solution_banners');
    }
}
