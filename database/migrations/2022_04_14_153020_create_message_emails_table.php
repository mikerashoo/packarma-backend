<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_emails', function (Blueprint $table) {
            $table->id();
            $table->text('mail_key', 20);
            $table->enum('user_type', ['all','customer','vendor'])->default('all')->comment('all|customer|vendor');
            $table->integer('language_id')->default(0);
            $table->string('title', 150);
            $table->string('from_name', 20);
            $table->string('from_email', 255);
            $table->string('to_name', 255)->nullable();
            $table->text('cc_email')->nullable();
            $table->text('subject');
            $table->text('label')->nullable();
            $table->longText('content');
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
        Schema::dropIfExists('message_emails');
    }
}
