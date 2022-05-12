<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name', 100);
            $table->string('vendor_company_name', 100);
            $table->string('vendor_email', 50);
            $table->string('vendor_password');
            $table->text('vendor_address')->nullable();
            $table->string('pincode',10)->nullable();
            $table->integer('phone_country_id')->default(0)->comment('phone_code');
            $table->string('phone', 15);
            $table->integer('whatsapp_country_id')->default(0)->comment('whatsapp_phone_code');
            $table->string('whatsapp_no', 20)->nullable();
            $table->integer('currency_id')->default(1);
            $table->enum('is_featured', [1, 0])->default(0);
            $table->string('approval_status', 255)->default('pending')->comment('pending|accepted|rejected');
            $table->datetime('approved_on')->nullable();
            $table->integer('approved_by')->default(0)->comment('Admin Id');
            $table->longText('admin_remark')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
