<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageWhatsappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->enum('user_type', ['all','customer','vendor'])->default('all')->comment('all|customer|vendor');
            $table->integer('language_id')->default(0);
            $table->string('params', 255);
            $table->string('operation', 30);
            $table->longText('message');
            $table->enum('gender', ['M','F','All'])->default('All');
            $table->string('file_attached', 255)->nullable();
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
        Schema::dropIfExists('message_whatsapps');
    }
}
