<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->enum('type', ['http', 'socks'])->nullable();
            $table->string('country_city')->nullable();
            $table->boolean('status');
            $table->unsignedFloat('speed')->nullable();
            $table->string('real_ip')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('log_id')->references('id')->on('proxy_log');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proxies');
    }
}
