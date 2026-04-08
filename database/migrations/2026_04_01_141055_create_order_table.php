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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('id_pesanan')->nullable();
            $table->string('pelanggan')->nullable();
            $table->string('kategori')->nullable();
            $table->string('pembayaran')->nullable();
            $table->integer('jumlah')->default(0);
            $table->string('status')->default('dikemas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
