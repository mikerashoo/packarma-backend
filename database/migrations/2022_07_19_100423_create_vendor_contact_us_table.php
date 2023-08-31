<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVendorContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_contact_us', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_id')->default(0);
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('mobile', 100);
            $table->string('subject', 255);
            $table->longText('details')->nullable();
            $table->string('call_from', 255)->default('mobile')->comment('website|android|ios|mobile|facebook|twitter|youtube|social|telecaller|others');
            $table->string('ip_address', 255)->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_contact_us');
    }
}
