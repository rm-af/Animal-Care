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
        Schema::table('antrian', function (Blueprint $table) {
            $table->foreign(['hewanId'], 'fk_hewan_antrian')->references(['hewanId'])->on('hewan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['layananId'], 'fk_layanan_antrian')->references(['layananId'])->on('layanan')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['userId'], 'fk_user_antrian')->references(['userId'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrian', function (Blueprint $table) {
            $table->dropForeign('fk_hewan_antrian');
            $table->dropForeign('fk_layanan_antrian');
            $table->dropForeign('fk_user_antrian');
        });
    }
};
