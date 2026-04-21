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
//         Schema::table('loans', function (Blueprint $table) {
// $table->enum('verification_status', ['pending', 'verified_returned', 'rejected_return'])
//                   ->default('pending')
//                   ->after('status')
//                   ->nullable(false);
//             $table->unsignedInteger('pending_return_quantity')->default(0)->after('verification_status');
//         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('verification_status');
        });
    }
};

