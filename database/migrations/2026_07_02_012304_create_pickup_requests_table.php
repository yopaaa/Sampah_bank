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
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->string('lokasi', 100)->default('bangka');
            $table->string('koordinat', 50);
            $table->enum('status', ['menunggu', 'disetujui', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->string('bukti', 255)->nullable();
            $table->integer('jumlah_plastik')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
