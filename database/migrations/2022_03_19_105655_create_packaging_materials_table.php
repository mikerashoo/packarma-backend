<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging_materials', function (Blueprint $table) {
            $table->id();
            $table->string('packaging_material_name', 255);
            $table->longText('material_description')->nullable();
            $table->integer('packing_type_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('shelf_life')->default(0);
            $table->decimal('approx_price', $precision = 8, $scale = 3)->default(0);
            $table->string('wvtr', 255);
            $table->string('otr', 255);
            $table->string('cof', 255);
            $table->string('sit', 255);
            $table->string('gsm', 255);
            $table->longText('special_feature');
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();
            $table->enum('status', [1, 0])->default(1);
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
        Schema::dropIfExists('packaging_materials');
    }
}
