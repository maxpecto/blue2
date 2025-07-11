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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->onDelete('cascade');
            $table->foreignId('maintenance_rule_id')->nullable()->constrained('maintenance_rules')->onDelete('set null');
            $table->date('tarih');
            $table->unsignedInteger('bakim_km');
            $table->json('kullanilan_parcalar')->nullable(); // ['part_id' => 1, 'quantity' => 2, 'price' => 100]
            $table->decimal('iscilik_ucreti', 10, 2)->nullable();
            $table->decimal('toplam_maliyet', 10, 2);
            $table->text('aciklama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
