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
            $table->renameColumn('8_500g', '0_500g');
            $table->renameColumn('501_1008g', '501_1000g');

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
            $table->renameColumn('0_500g', '8_500g');
            $table->renameColumn('501_1000g', '501_1008g');
        });
    }
};
