<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplyingForsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applying_fors', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->string('full_name');
            $table->string('code_mli');
            $table->string('phone');
            $table->string('user_id');
            $table->string('javab')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('applying_fors');
    }
}
