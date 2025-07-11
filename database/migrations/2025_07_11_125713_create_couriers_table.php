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
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('ad');
            $table->string('soyad');
            $table->string('ata_adi');
            $table->string('seria_no')->unique();
            $table->string('fin_kodu')->unique();
            $table->date('dogum_tarihi');
            $table->string('telefon_no');
            $table->text('adres_1');
            $table->text('adres_2')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('akraba_tel_1');
            $table->string('akraba_tel_2')->nullable();
            $table->string('akraba_tel_3')->nullable();
            $table->enum('status', ['aktif', 'pasif', 'kara_listede'])->default('aktif');
            $table->json('belgeler')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
