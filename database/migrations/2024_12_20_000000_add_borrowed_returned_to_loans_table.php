<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->unsignedInteger('borrowed_quantity')->default(0)->after('quantity');
            $table->unsignedInteger('returned_quantity')->default(0)->after('borrowed_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['borrowed_quantity', 'returned_quantity']);
        });
    }
};

