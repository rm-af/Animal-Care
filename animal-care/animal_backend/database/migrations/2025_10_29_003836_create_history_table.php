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
        Schema::create('history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('antrian_id')->index('antrian_id');
            $table->integer('userId')->nullable();
            $table->integer('hewanId')->nullable();
            $table->integer('layananId')->nullable();
            $table->string('nama_hewan', 100)->nullable();
            $table->text('keluhan')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->enum('status', ['menunggu', 'ditangani'])->nullable()->default('ditangani');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
