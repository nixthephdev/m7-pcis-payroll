<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('students', 'guardian_email')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('guardian_email')->nullable()->after('guardian_contact');
            });
        }
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('guardian_email');
        });
    }
};
