<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'flair')) {
                $table->integer('flair')->default(0);
            }
            if (!Schema::hasColumn('services', 'essence')) {
                $table->integer('essence')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'flair')) {
                $table->dropColumn('flair');
            }
            if (!Schema::hasColumn('services', 'essence')) {
                $table->dropColumn('essence');
            }
        });
    }
}
