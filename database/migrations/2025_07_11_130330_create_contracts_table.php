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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('sozlesme_no')->unique();
            $table->foreignId('courier_id')->constrained('couriers')->onDelete('cascade');
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->onDelete('cascade');
            $table->enum('type', ['kiralama', 'taksitli_satis']);
            $table->decimal('toplam_tutar', 10, 2);
            $table->decimal('aylik_odeme', 10, 2);
            $table->unsignedInteger('odeme_periyodu'); // Kaç günde bir
            $table->date('baslangic_tarihi');
            $table->date('bitis_tarihi');
            $table->enum('status', ['aktif', 'tamamlandi', 'iptal_edildi'])->default('aktif');
            $table->json('teslimat_gorselleri')->nullable();
            $table->string('sozlesme_pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
