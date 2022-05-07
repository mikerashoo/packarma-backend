<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecommendationEnginesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recommendation_engines', function (Blueprint $table) {
            $table->id();
            $table->string('engine_name', 255);
            $table->string('structure_type', 255);// 
            $table->integer('product_id')->default(0);
            $table->integer('min_shelf_life')->default(0);
            $table->integer('max_shelf_life')->default(0);
            $table->decimal('min_weight', $precision = 8, $scale = 3)->default(0.000);
            $table->decimal('max_weight', $precision = 8, $scale = 3)->default(0.000);
            $table->decimal('approx_price', $precision = 8, $scale = 3)->default(0.000);
            $table->integer('category_id')->default(0);
            $table->integer('product_form_id')->default(0);
            $table->integer('packing_type_id')->default(0);
            $table->integer('packaging_machine_id')->default(0);
            $table->integer('packaging_treatment_id')->default(0);
            $table->integer('packaging_material_id')->default(0);
            $table->integer('storage_condition_id')->default(0);
            $table->integer('vendor_id')->default(0);
            $table->integer('display_shelf_life')->default(0);
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
        Schema::dropIfExists('recommendation_engines');
    }
}
