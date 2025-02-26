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
        Schema::create('t_stok', function (Blueprint $table) {
            $table->id('stok_id'); // Primary Key (Auto Increment)
            $table->unsignedBigInteger('supplier_id'); // Foreign Key dari m_supplier
            $table->unsignedBigInteger('barang_id'); // Foreign Key dari m_barang
            $table->unsignedBigInteger('user_id'); // Foreign Key dari m_user
            $table->datetime('stok_tanggal');
            $table->integer('stok_jumlah');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('supplier_id')->references('supplier_id')->on('m_supplier')->onDelete('cascade');
            $table->foreign('barang_id')->references('barang_id')->on('m_barang')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('m_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok');
    }
};