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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
        });

        // Optional: Data migration to normalize existing statuses
        // DB::table('transactions')->whereIn('status', ['success', 'resolved', 'in_progress', 'approved', 'completed'])->update(['status' => 'successful']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed', 'rejected', 'query'])->default('pending')->change();
        });
    }
};
