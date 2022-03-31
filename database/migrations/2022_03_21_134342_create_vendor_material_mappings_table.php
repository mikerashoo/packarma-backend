<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorMaterialMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_material_mappings', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id');
            $table->integer('packaging_material_id');
            $table->integer('recommendation_engine_id')->default(0)->comment('Mapping with ID');
            $table->integer('product_id')->default(0);
            $table->decimal('min_amt_profit', $precision = 8, $scale = 3)->comment('Per Kg');
            $table->decimal('min_stock_qty', $precision = 8, $scale = 3);
            $table->decimal('vendor_price', $precision = 8, $scale = 3)->comment('Per Kg');
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
        Schema::dropIfExists('vendor_material_mappings');
    }
}
