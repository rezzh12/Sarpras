<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prasarana extends Model
{
    use HasFactory;
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id','id')
                        ->withDefault(['ruangan_id' => 'Ruangan Belum Dipilih']);
    }
}
