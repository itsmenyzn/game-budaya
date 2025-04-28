<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budaya extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'budaya';

    protected $primaryKey = 'id_budaya';

    protected $fillable = [
        'nama_budaya',
        'jenis_budaya',
        'tipe_budaya',
        'deskripsi',
        'attachment'
    ];
}

