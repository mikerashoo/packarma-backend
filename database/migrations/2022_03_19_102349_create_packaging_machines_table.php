<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagingMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging_machines', function (Blueprint $table) {
            $table->id();
            $table->string('packaging_machine_name', 255);
            $table->longText('packaging_machine_description')->nullable();
            $table->string('packaging_machine_image', 100)->nullable();
            $table->string('packaging_machine_thumb_image', 100)->nullable();
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
        Schema::dropIfExists('packaging_machines');
    }
}
