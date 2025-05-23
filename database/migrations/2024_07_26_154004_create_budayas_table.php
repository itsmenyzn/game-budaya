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
        Schema::create('budaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis',["rumah","pakaian","tari","alat_musik"]);
            $table->enum('tipe',["audio","visual"]);
            $table->text('description');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budaya');
    }
};
