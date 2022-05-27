<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEnquiryStatusColumnCommentToVendorQuotationTable extends Migration
{
    /**
     * Created By : Pradyumn Dwivedi
     * Created at : 26/05/2022
     * Uses : Adding auto_reject comment value to customer enquiry status in vendor quotation table 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->string('enquiry_status', 255)->default('mapped')->comment('mapped|quoted|viewed|accept|reject|requote|auto_reject')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_quotations', function (Blueprint $table) {
            $table->dropColumn('enquiry_status');
        });
    }
}
