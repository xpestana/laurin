<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('annuled')->default(0);
            $table->boolean('goa')->default(0);
            $table->string('service')->nullable();
            $table->string('enterprise')->nullable();
            $table->dateTime('date_Time')->nullable();
            $table->integer('timeservices')->nullable();
            $table->integer('base');
            $table->string('destination')->nullable();
            $table->string('destination_lat')->nullable();
            $table->string('destination_long')->nullable();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('file');
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
        Schema::dropIfExists('services');
    }
}
