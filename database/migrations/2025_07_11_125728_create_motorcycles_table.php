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
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->string('plaka')->unique();
            $table->string('marka');
            $table->string('model');
            $table->string('motor_no')->unique();
            $table->string('sase_no')->unique();
            $table->string('motor_hacmi');
            $table->string('tip');
            $table->string('renk');
            $table->decimal('alis_fiyati', 10, 2);
            $table->unsignedInteger('alis_km');
            $table->unsignedInteger('mevcut_km');
            $table->enum('status', ['depoda', 'kirada', 'satildi', 'serviste', 'pert'])->default('depoda');
            $table->json('gorseller')->nullable();
            $table->json('belgeler')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
