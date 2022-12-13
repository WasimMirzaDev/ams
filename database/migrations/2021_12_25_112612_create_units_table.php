<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('building_id')->unsigned();
            $table->integer('number')->default(0);
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('booked')->default(0);
            $table->decimal('weekly_rent', 10, 2)->default(0);
            $table->decimal('monthly_rent', 10, 2)->default(0);
            $table->decimal('yearly_rent', 10, 2)->default(0);
            $table->integer('active')->default(1);
            $table->timestamps();
        });
        Schema::table('units', function($table) {
       $table->foreign('building_id')->references('id')->on('buildings');
   });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
