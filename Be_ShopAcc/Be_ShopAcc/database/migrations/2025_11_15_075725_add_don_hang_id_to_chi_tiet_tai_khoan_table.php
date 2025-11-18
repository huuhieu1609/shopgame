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
        Schema::table('chi_tiet_tai_khoan', function (Blueprint $table) {
            $table->unsignedBigInteger('don_hang_id')->nullable()->after('san_pham_id');
            $table->foreign('don_hang_id')->references('id')->on('don_hang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_tai_khoan', function (Blueprint $table) {
            $table->dropForeign(['don_hang_id']);
            $table->dropColumn('don_hang_id');
        });
    }
};
