<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IHHQSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for countries
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('country_id');
            $table->string('country_name')->unique();
            $table->string('phone_code')->unique();
        });

        // Create table for departments
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('department_id');
            $table->string('department_name')->unique();
            $table->timestamps();
        });

        // Create table for files
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('file_id');
            $table->string('file_type', 50);
            $table->string('project_name', 100);
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
        //
    }
}
