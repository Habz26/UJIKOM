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
        Schema::table('books', function (Blueprint $table) {
            $table->string('publisher')->after('year');
// $table->string('category')->after('publisher'); // Deprecated, use id_kategori instead
$table->foreignId('id_kategori')->nullable()->after('publisher')
    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
$table->dropForeign(['id_kategori']);
$table->dropColumn(['publisher', 'id_kategori', 'category']);
        });
    }
};

