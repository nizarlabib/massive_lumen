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
        Schema::create('wisata', function (Blueprint $table) {
            $table->id('id_wisata');
            $table->string('nama_wisata');
            $table->text('deskripsi_wisata');
            $table->string('gambar_wisata')->nullable();
            $table->string('alamat_wisata');
            $table->string('jam_buka_wisata');
            $table->string('harga_tiket_wisata');
            $table->foreignId('id_kategori_wisata')->unsigned();
            $table->foreignId('id_user')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
