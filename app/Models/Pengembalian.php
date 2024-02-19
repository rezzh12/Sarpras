<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    public function barang()
    {
        return $this->belongsTo(Peminjaman::class, 'kode_peminjaman','kode')
                        ->withDefault(['kode_peminjaman' => 'Kode Belum Dipilih']);
    }
}
