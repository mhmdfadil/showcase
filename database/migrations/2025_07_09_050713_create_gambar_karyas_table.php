<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gambar_karyas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_id')->constrained('karyas')->onDelete('cascade');
            $table->string('nama_gambar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gambar_karyas');
    }
};