<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('rating')->default(0);
            $table->text('title')->default(NULL);
            $table->longText('review')->default(NULL);
            $table->string('approval_status', 255)->default('pending')->comment('pending|approved|rejected');
            $table->datetime('approved_on');
            $table->integer('approved_by')->default(0)->comment('Admin Id');
            $table->longText('admin_remark')->default(NULL);
            $table->enum('status', [1, 0])->default(0);
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
        Schema::dropIfExists('reviews');
    }
}
