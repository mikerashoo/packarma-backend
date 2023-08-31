<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_enquiries', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->string('enquiry_type', 255)->default('general')->comment('general|engine');
            $table->integer('user_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('shelf_life')->default(0);
            $table->decimal('product_weight', $precision = 8, $scale = 3)->default(0.000);
            $table->integer('measurement_unit_id')->default(0);
            $table->integer('product_quantity')->default(0);
            $table->integer('storage_condition_id')->default(0);
            $table->integer('packaging_machine_id')->default(0);
            $table->integer('product_form_id')->default(0);
            $table->integer('packing_type_id')->default(0);
            $table->integer('packaging_treatment_id')->default(0);
            $table->longText('address')->nullable();
            $table->integer('country_id')->default(1);
            $table->integer('city_id')->default(0);
            $table->integer('state_id')->default(0); 
            $table->integer('pincode')->default(0);
            $table->string('quote_type', 255)->default('enquired')->comment('enquired|map_to_vendor|accept_cust|closed');
            $table->enum('status', [1, 0])->default(0);
            $table->string('seo_url', 255)->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();
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
        Schema::dropIfExists('customer_enquiries');
    }
}
