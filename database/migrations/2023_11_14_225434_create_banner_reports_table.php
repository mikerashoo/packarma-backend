<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBannerReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement(" CREATE VIEW banner_reports AS
        SELECT
            ROW_NUMBER() OVER (ORDER BY bc.created_at) AS id,
            u.id AS user,
            u.name,
            u.email,
            bc.solution_banner_id AS solution_banner_id,
            bc.banner_id AS banner_id,
            bc.created_at AS date,
            COALESCE(b.title, sb.title) AS banner_title,
            true AS is_click
        FROM
            banner_clicks bc
            LEFT JOIN users u ON bc.user_id = u.id
            LEFT JOIN banners b ON bc.banner_id = b.id
            LEFT JOIN solution_banners sb ON bc.solution_banner_id = sb.id

        UNION ALL

        SELECT
            ROW_NUMBER() OVER (ORDER BY bv.created_at) AS id,
            u.id AS user,
            u.name,
            u.email,
            bv.solution_banner_id AS solution_banner_id,
            bv.banner_id AS banner_id,
            bv.created_at AS date,
            COALESCE(b.title, sb.title) AS banner_title,
            false AS is_click
        FROM
            banner_views bv
            LEFT JOIN users u ON bv.user_id = u.id
            LEFT JOIN banners b ON bv.banner_id = b.id
            LEFT JOIN solution_banners sb ON bv.solution_banner_id = sb.id;
         ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW banner_reports");
    }
}
