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
        Schema::create('payrolls', function (Blueprint $table) {
    $table->id();
    $table->foreignId('employee_id')->constrained();
    $table->date('pay_date');
    $table->decimal('gross_salary', 10, 2);
    $table->decimal('deductions', 10, 2);
    $table->decimal('net_salary', 10, 2);
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
        Schema::dropIfExists('payrolls');
    }
};
