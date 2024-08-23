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
        Schema::create('jasas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
            $table->string('nama_jasa');
            $table->foreignId('kategori_jasa_id')->constrained('kategori_jasas');
            $table->string('deskripsi');
            $table->string('kontak')->nullable();
            $table->string('alamat');
            $table->string('harga')->nullable();
            $table->string('waktu')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasas');
    }
};
