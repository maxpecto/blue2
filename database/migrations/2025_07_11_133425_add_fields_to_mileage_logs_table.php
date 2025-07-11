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
        Schema::table('mileage_logs', function (Blueprint $table) {
            $table->foreignId('motor_id')->constrained('motorcycles')->onDelete('cascade');
            $table->unsignedInteger('km');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mileage_logs', function (Blueprint $table) {
            $table->dropForeign(['motor_id']);
            $table->dropColumn(['motor_id', 'km', 'notes']);
        });
    }
};
