<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmsNotificationAndEmailNotificationAndWhatsappNotificationAndFcmNotificationToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->enum('sms_notification', [1, 0])->default(1)->after('status');
            $table->enum('email_notification', [1, 0])->default(1)->after('sms_notification');
            $table->enum('whatsapp_notification', [1, 0])->default(1)->after('email_notification');
            $table->enum('fcm_notification', [1, 0])->default(1)->after('whatsapp_notification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            //
        });
    }
}
