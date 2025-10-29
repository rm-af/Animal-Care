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
        Schema::create('antrian', function (Blueprint $table) {
            $table->integer('antrianId', true);
            $table->integer('userId')->nullable()->index('fk_user_antrian');
            $table->integer('hewanId')->nullable()->index('fk_hewan_antrian');
            $table->integer('layananId')->nullable()->index('fk_layanan_antrian');
            $table->string('nama_hewan', 100)->nullable();
            $table->text('keluhan')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->enum('status', ['menunggu', 'ditangani'])->nullable()->default('menunggu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};
