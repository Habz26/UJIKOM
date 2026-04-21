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
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('fine_paid', 10, 2)->default(0)->after('fine');
            $table->enum('fine_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('fine_paid');
            $table->timestamp('fine_paid_at')->nullable()->after('fine_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['fine_paid', 'fine_status', 'fine_paid_at']);
        });
    }
};

