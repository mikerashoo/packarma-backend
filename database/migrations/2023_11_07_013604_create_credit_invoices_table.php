<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCreditInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        DB::statement("CREATE VIEW credit_invoices AS
        SELECT
          u.id AS user_id,
          c.id AS credit_id,
          a.id AS address_id,
          c.amount_paid AS amount
        FROM users u
        LEFT JOIN user_credit_histories c ON u.id = c.user_id
        LEFT JOIN invoice_addresses a ON u.id = a.user_id;");

        DB::statement("CREATE VIEW user_subscription_invoices AS
        SELECT
        u.id AS user_id,
        s.id AS subscription_id,
        a.id AS address_id,
        s.amount AS amount
        FROM users u
        LEFT JOIN user_subscription_payments s ON u.id = s.user_id
        LEFT JOIN invoice_addresses a ON u.id = a.user_id");

        DB::statement("CREATE VIEW user_invoices AS
        SELECT ROW_NUMBER() OVER() AS id, user_id, credit_id, subscription_id, amount
        FROM
        (
            SELECT user_id, credit_id, NULL AS subscription_id, amount
            FROM credit_invoices

            UNION

            SELECT user_id, NULL AS credit_id, subscription_id, amount
            FROM user_subscription_invoices
        ) inv");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW credit_invoices");
        DB::statement("DROP VIEW user_subscription_invoices");
    }
}
