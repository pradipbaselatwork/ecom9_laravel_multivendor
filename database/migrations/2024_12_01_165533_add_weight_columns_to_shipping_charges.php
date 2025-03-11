<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->float('8_500g')->after('country');
            $table->float('501_1008g')->after('8_500g');
            $table->float('1001_2000g')->after('501_1008g');
            $table->float('2001_5000g')->after('1001_2000g');
            $table->float('above_5000g')->after('2001_5000g');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->dropColumn('8_500g');
            $table->dropColumn('501_1008g');
            $table->dropColumn('1001_2000g');
            $table->dropColumn('2001_5000g');
            $table->dropColumn('above_5000g');
        });
    }
};
