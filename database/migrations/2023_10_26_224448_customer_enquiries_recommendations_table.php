<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerEnquiriesRecommendationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_enquiries_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('customer_enquiry_id')->unsigned();
            $table->unsignedBiginteger('recommendation_id')->unsigned();

            $table->foreign('customer_enquiry_id')->references('id')
                ->on('customer_enquiries')->onDelete('cascade');
            $table->foreign('recommendation_id')->references('id')
                ->on('recommendation_engines')->onDelete('cascade');

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
        Schema::dropIfExists('customer_enquiries_recommendations');
    }
}
