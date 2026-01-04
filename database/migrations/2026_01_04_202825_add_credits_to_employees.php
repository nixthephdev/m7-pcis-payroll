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
    Schema::table('employees', function (Blueprint $table) {
        if (!Schema::hasColumn('employees', 'vacation_credits')) {
            $table->integer('vacation_credits')->default(15)->after('basic_salary');
        }
        if (!Schema::hasColumn('employees', 'sick_credits')) {
            $table->integer('sick_credits')->default(15)->after('vacation_credits');
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
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
