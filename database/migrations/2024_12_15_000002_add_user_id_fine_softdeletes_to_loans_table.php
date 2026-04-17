<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->after('id');
            $table->decimal('fine', 10, 2)->default(0)->after('returned_at');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'fine', 'deleted_at']);
        });
    }
};

