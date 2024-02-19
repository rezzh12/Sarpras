<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_Keluar extends Model
{
    use HasFactory;
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id','id')
                        ->withDefault(['barang_id' => 'Barang Belum Dipilih']);
    }
}
