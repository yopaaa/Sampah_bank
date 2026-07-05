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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('saldo')->default(0)->after('avatar');
        });

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('jenis_sampah', 50)->default('plastik')->after('jumlah_plastik');
            $table->integer('total_harga')->default(0)->after('jenis_sampah');
            $table->foreignId('agent_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('saldo');
        });

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['jenis_sampah', 'total_harga', 'agent_id']);
        });
    }
};
