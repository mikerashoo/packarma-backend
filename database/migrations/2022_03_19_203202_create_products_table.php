<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 255);
            $table->longText('product_description')->nullable();
            $table->integer('sub_category_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('product_form_id')->default(0);
            $table->integer('packaging_treatment_id')->default(0);
            $table->string('product_image', 100)->nullable();
            $table->string('product_thumb_image', 100)->nullable();
            $table->longText('machine_data')->default(0)->comment('Json Data');
            $table->longText('storage_condition_data')->default(0)->comment('Json Data');
            $table->longText('product_form_data')->default(0)->comment('Json Data');
            $table->longText('packaging_treatment_data')->default(0)->comment('Json Data');
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
        Schema::dropIfExists('products');
    }
}
