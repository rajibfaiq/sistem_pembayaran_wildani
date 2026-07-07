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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('paid_at');
            $table->string('whatsapp_number', 20)->nullable()->after('payment_proof');
            $table->string('rejected_reason')->nullable()->after('whatsapp_number');
        });

        // Rebuild status column to include new statuses
        Schema::table('bills', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'whatsapp_number', 'rejected_reason']);
        });
    }
};
