<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = ['judul', 'file_pdf', 'link_video', 'file_video', 'pelatih_id'];

    public function pelatih()
    {
        return $this->belongsTo(User::class, 'pelatih_id');
    }
}
