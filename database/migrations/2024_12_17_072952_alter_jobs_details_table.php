<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_details', function (Blueprint $table) {
            // user_id کالم شامل کریں
            $table->foreignId('user_id')
                ->constrained()
                ->after('job_type_id') // اگر آپ کو ترتیب درکار ہے
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_details', function (Blueprint $table) {
            // کالم اور foreign key ہٹائیں
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
