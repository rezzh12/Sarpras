<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianRuangan extends Model
{
    use HasFactory;
    public function ruangan()
    {
        return $this->belongsTo(PinjamRuangan::class, 'kode_peminjaman_ruangan','kode')
                        ->withDefault(['kode_peminjaman_ruangan' => 'Ruangan Belum Dipilih']);
    }
}
