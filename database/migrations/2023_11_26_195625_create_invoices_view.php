<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        CREATE VIEW invoices AS
        SELECT
            ROW_NUMBER() OVER (ORDER BY subscriptions.id) AS id,
            subscriptions.id AS subscription_id,
            NULL AS credit_id,
            subscriptions.amount AS amount,
            subscriptions.user_id AS user_id,
            subscriptions.transaction_id AS transaction_id
        FROM
            user_subscription_payments AS subscriptions
        UNION
        SELECT
            ROW_NUMBER() OVER (ORDER BY credits.id) + (SELECT MAX(id) FROM user_subscription_payments) AS id,
            NULL AS subscription_id,
            credits.id AS credit_id,
            credits.amount_paid AS amount,
            credits.user_id AS user_id,
            credits.transaction_id AS transaction_id
        FROM
            user_credit_histories AS credits;
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW invoices");

    }
}
