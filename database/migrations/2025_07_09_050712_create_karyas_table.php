<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karyas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('slug')->unique();
            $table->enum('jenis_karya', ['video', 'gambar', 'dokumen']);
            $table->year('tahun');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['MENUNGGU', 'DISETUJUI', 'DITOLAK', 'PERBAIKAN'])->default('MENUNGGU');
            $table->string('video_karya')->nullable();
            $table->string('dokumen_karya')->nullable();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_publish')->nullable();
            $table->text('keterangan_revisi')->nullable();
            $table->integer('views')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.0);
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyas');
    }
};