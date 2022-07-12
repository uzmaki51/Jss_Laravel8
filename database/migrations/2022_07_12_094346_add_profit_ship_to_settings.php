<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfitShipToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_settings', function (Blueprint $table) {
            $table->string('profit_ship', 100)->default('[]')->after('profit_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_settings', function (Blueprint $table) {
            $table->dropColumn('profit_ship');
        });
    }
}
