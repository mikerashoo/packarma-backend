<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubscriptionDataSeederMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('subscriptions')->truncate();
        Artisan::call('db:seed', [
            '--class' => 'SubscriptionDurationSeeder',
            '--force' => true
        ]

        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('subscriptions')->truncate();
    }
}
