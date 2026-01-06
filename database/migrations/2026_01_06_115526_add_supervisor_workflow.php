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
        // 1. Add Supervisor to Employees
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('user_id');
        });

        // 2. Add Supervisor Status to Leave Requests
        Schema::table('leave_requests', function (Blueprint $table) {
            // Values: 'Pending', 'Approved', 'Rejected', 'N/A' (if no supervisor)
            $table->string('supervisor_status')->default('Pending')->after('reason');
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
};
