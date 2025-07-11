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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('parca_adi');
            $table->string('parca_kodu')->unique()->nullable();
            $table->string('marka')->nullable();
            $table->text('uyumlu_modeller')->nullable();
            $table->decimal('alis_fiyati', 10, 2);
            $table->decimal('satis_fiyati', 10, 2);
            $table->unsignedInteger('stok_adedi')->default(0);
            $table->unsignedInteger('minimum_stok_seviyesi')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
