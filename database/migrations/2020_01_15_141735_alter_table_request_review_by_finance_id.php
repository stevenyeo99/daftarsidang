<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRequestReviewByFinanceId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function(Blueprint $table) {
            $table->integer('review_finance_user_id')->nullable()->unsigned();
            $table->foreign('review_finance_user_id')->references('id')->on('finance_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('requests', function(Blueprint $table) {
            $table->dropColumn(['review_finance_user_id']);
        });
    }
}
