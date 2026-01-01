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
    Schema::table('attendances', function (Blueprint $table) {
        // Drop the old foreign key constraint first (Important!)
        // Note: The constraint name is usually 'attendances_employee_id_foreign'
        // If this fails, we might need to check your specific constraint name.
        $table->dropForeign(['employee_id']);
        
        // Drop the column
        $table->dropColumn('employee_id');

        // Add the new dynamic columns
        $table->unsignedBigInteger('attendable_id');
        $table->string('attendable_type'); // Stores "App\Models\Employee" or "App\Models\Student"
        
        // Index for speed
        $table->index(['attendable_id', 'attendable_type']);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
        });
    }
};
