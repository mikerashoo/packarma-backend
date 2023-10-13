<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('phone_country_id')->default(0)->comment('phone_code');
            $table->string('phone', 15);
            $table->integer('whatsapp_country_id')->default(0)->comment('whatsapp_phone_code');
            $table->string('whatsapp_no', 20)->nullable();
            $table->integer('language_id')->default(0);
            $table->integer('currency_id')->default(1);
            $table->string('gstin',15)->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('approval_status', 255)->default('pending')->comment('pending|accepted|rejected');
            $table->datetime('approved_on')->nullable();
            $table->integer('approved_by')->default(0)->comment('Admin Id');
            $table->longText('admin_remark')->nullable();
            $table->integer('subscription_id')->default(0);
            $table->datetime('subscription_start')->nullable();
            $table->datetime('subscription_end')->nullable();
            $table->string('type', 255)->default('normal')->comment('normal|premium');
            $table->enum('status', [1, 0])->default(0);
            $table->enum('sms_notification', [1, 0])->default(1);
            $table->enum('email_notification', [1, 0])->default(1);
            $table->enum('whatsapp_notification', [1, 0])->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->longText('mkey')->nullable();
            $table->longText('msalt')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
