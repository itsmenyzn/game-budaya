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
        Schema::table('budaya',function(Blueprint $table){
            $table->renameColumn('id','id_budaya');
            $table->renameColumn('nama','nama_budaya');
            $table->renameColumn('tipe','tipe_budaya');
            $table->renameColumn('jenis','jenis_budaya');
            $table->renameColumn('description','deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
