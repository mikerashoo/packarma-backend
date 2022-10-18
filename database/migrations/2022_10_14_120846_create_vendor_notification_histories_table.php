<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorNotificationHistoriesTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 14-Oct-2022
     * Use : To create table for vendor notification history
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_notification_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->string('imei_no', 255)->nullable()->comment('device_id');
            $table->integer('language_id')->default(0);
            $table->string('notification_name', 255)->nullable();
            $table->string('page_name', 255)->nullable()->comment('type');
            $table->integer('type_id')->default(0);
            $table->string('title', 255);
            $table->text('body')->nullable();
            $table->string('notification_image', 255)->nullable();
            $table->string('notification_thumb_image', 255)->nullable();
            $table->datetime('notification_date')->nullable();
            $table->enum('trigger', ['manual','admin', 'batch'])->default('batch');
            $table->enum('is_read', [1, 0])->default(0);
            $table->enum('is_discard', [1, 0])->default(0);
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
        Schema::dropIfExists('vendor_notification_histories');
    }
}
