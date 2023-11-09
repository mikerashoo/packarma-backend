<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAppPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_pages', function (Blueprint $table) {
            $table->id();
            $table->string('pageName');
            $table->timestamps();
        });


        DB::table('app_pages')->insert(array(

            ["pageName" =>  "AdminApproval"],
            ["pageName" =>  "Success"],
            ["pageName" =>  "Subscription"],
            ["pageName" =>  "Product"],
            ["pageName" =>  "NotificationScreen"],
            ["pageName" =>  "PlaceEnquiry"],
            ["pageName" =>  "AlternateSolution"],
            ["pageName" =>  "SolutionDetails"],
            ["pageName" =>  "EnquiryDescription"],
            ["pageName" =>  "MyProfile"],
            ["pageName" =>  "ManageAddress"],
            ["pageName" =>  "AddAddress"],
            ["pageName" =>  "InvoiceDetails"],
            ["pageName" =>  "InvoicePreview"],
            ["pageName" =>  "OrderDetails"],
            ["pageName" =>  "AcceptQuataion"],
            ["pageName" =>  "SelectAddress"],
            ["pageName" =>  "SuccessFromAddress"],
            ["pageName" =>  "Treatment"],
            ["pageName" =>  "TreatmentDetails"],
            ["pageName" =>  "SearchScreen"],
            ["pageName" =>  "ChangePassword"],
            ["pageName" =>  "RequestDescription"],
            ["pageName" =>  "AboutUs"],
            ["pageName" =>  "TermsAndCondition"],
            ["pageName" =>  "PrivacyPolicy"],
            ["pageName" =>  "Help"],
            ["pageName" =>  "EditProfile"],
            ["pageName" =>  "GSTDetails"],
            ["pageName" =>  "ContactUsFromHome"],
            ["pageName" =>  "ContactUsFromGST"],
            ["pageName" =>  "SubscriptionHistory"],
            ["pageName" =>  "MyCredit"],
            ["pageName" =>  "CreditHistory"]

        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_pages');
    }
}
