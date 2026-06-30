<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = ['pelatih_id', 'nama_kelas', 'kategori_kelas', 'jenis_kelas', 'hari', 'jam'];

    public function pelatih()
    {
        return $this->belongsTo(User::class, 'pelatih_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public static function transformCollection($collection)
    {
        $user = auth()->user();
        $userTypes = ($user && $user->jenis_kelas) ? explode(',', $user->jenis_kelas) : [];
        $userHasBoth = ($user && $user->role === 'admin') || (in_array('rampak', $userTypes) && in_array('reguler', $userTypes));

        $grouped = $collection->groupBy('kategori_kelas');
        $expandedCollection = collect();

        foreach ($grouped as $kategori => $items) {
            $hasRampak = $items->contains('jenis_kelas', 'rampak');
            $hasReguler = $items->contains('jenis_kelas', 'reguler');

            $pelatihHasBoth = false;
            foreach ($items as $item) {
                if ($item->pelatih) {
                    $pelatihTypes = $item->pelatih->jenis_kelas ? explode(',', $item->pelatih->jenis_kelas) : [];
                    if (in_array('rampak', $pelatihTypes) && in_array('reguler', $pelatihTypes)) {
                        $pelatihHasBoth = true;
                        break;
                    }
                }
            }

            $shouldHaveBoth = $userHasBoth || $pelatihHasBoth;

            foreach ($items as $item) {
                $expandedCollection->push($item);
            }

            if ($shouldHaveBoth) {
                if ($hasRampak && !$hasReguler) {
                    $base = $items->firstWhere('jenis_kelas', 'rampak');
                    $dynamic = clone $base;
                    $dynamic->id = 0;
                    $dynamic->jenis_kelas = 'reguler';
                    $dynamic->nama_kelas = 'Kelas ' . ucfirst($kategori) . ' Reguler';
                    $dynamic->hari = 'Minggu';
                    $dynamic->jam = '14.00-15.00';
                    $expandedCollection->push($dynamic);
                } elseif (!$hasRampak && $hasReguler) {
                    $base = $items->firstWhere('jenis_kelas', 'reguler');
                    $dynamic = clone $base;
                    $dynamic->id = 0;
                    $dynamic->jenis_kelas = 'rampak';
                    $dynamic->nama_kelas = 'Kelas ' . ucfirst($kategori) . ' Rampak';
                    $dynamic->hari = 'Jumat';
                    $dynamic->jam = '14.00-17.00';
                    $expandedCollection->push($dynamic);
                }
            }
        }

        $transformed = collect();
        $seenKeys = [];

        foreach ($expandedCollection as $j) {
            $key = $j->kategori_kelas . '_' . $j->jenis_kelas;

            if ($j->jenis_kelas === 'reguler') {
                if (!in_array($key, $seenKeys)) {
                    $sabtu = clone $j;
                    $sabtu->hari = 'Sabtu';
                    $sabtu->jam = '14.00-15.00';

                    $minggu = clone $j;
                    $minggu->hari = 'Minggu';
                    $minggu->jam = '14.00-15.00';

                    $transformed->push($sabtu);
                    $transformed->push($minggu);
                    $seenKeys[] = $key;
                }
            } elseif ($j->jenis_kelas === 'rampak') {
                if (!in_array($key, $seenKeys)) {
                    $rampak = clone $j;
                    $rampak->hari = 'Jumat';
                    $rampak->jam = '14.00-17.00';
                    $transformed->push($rampak);
                    $seenKeys[] = $key;
                }
            } else {
                $transformed->push($j);
            }
        }

        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7
        ];

        if ($user && $user->role === 'murid') {
            $transformed = $transformed->filter(function ($item) use ($userTypes) {
                return in_array(strtolower($item->jenis_kelas), array_map('strtolower', $userTypes));
            });
        }

        return $transformed->sortBy(function ($item) use ($dayOrder) {
            return [$dayOrder[$item->hari] ?? 99, $item->jam];
        })->values();
    }
}
