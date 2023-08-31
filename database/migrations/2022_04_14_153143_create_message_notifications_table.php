<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type', ['all','customer','vendor'])->default('all')->comment('all|customer|vendor');
            $table->integer('language_id')->default(0);
            $table->string('page_name', 255)->nullable();
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->enum('gender', ['M','F','All'])->default('All');
            $table->string('notification_image', 255)->nullable();
            $table->string('notification_thumb_image', 255)->nullable();
            $table->datetime('notification_date')->nullable();
            $table->enum('trigger', ['both','admin', 'batch'])->default('both');
            $table->enum('status', [1, 0])->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('message_notifications');
    }
}
