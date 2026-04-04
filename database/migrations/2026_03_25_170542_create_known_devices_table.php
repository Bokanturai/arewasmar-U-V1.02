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
        Schema::create('known_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fingerprint')->index();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('last_login_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('known_devices');
    }
};
