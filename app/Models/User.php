<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'no_hp',
        'foto',
        'kode_pelatih',
        'kategori_kelas',
        'jenis_kelas',
        'status',
        'last_active_at',
        'reactivated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active_at' => 'datetime',
        'reactivated_at' => 'datetime',
    ];

    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'data:image/') || str_starts_with($this->foto, 'data:')) {
            return $this->foto;
        }

        return asset('storage/' . $this->foto);
    }

    public function isAdmin()    { return $this->role === 'admin'; }
    public function isPelatih()  { return $this->role === 'pelatih'; }
    public function isMurid()    { return $this->role === 'murid'; }

    public function getClassDays()
    {
        $days = [];
        if (!$this->jenis_kelas) {
            return [];
        }
        
        $types = explode(',', $this->jenis_kelas);
        foreach ($types as $type) {
            $type = trim(strtolower($type));
            if (str_contains($type, 'rampak')) {
                $days[] = 'Friday';
            } elseif (str_contains($type, 'reguler')) {
                $days[] = 'Saturday';
                $days[] = 'Sunday';
            }
        }
        return array_unique($days);
    }

    /**
     * Menghitung berapa hari kelas yang terlewat semenjak absensi hadir terakhir.
     */
    public function getMissedClassDaysCount(): int
    {
        $classDays = $this->getClassDays();
        if (empty($classDays)) {
            return 0;
        }

        // Cari absensi hadir terakhir
        $lastHadir = $this->absensi()
            ->where('status', 'hadir')
            ->orderByDesc('tanggal')
            ->first();

        if (!$lastHadir) {
            // Belum pernah hadir — cek dari last_active_at jika ada, atau tanggal dibuat
            $refDate = $this->last_active_at 
                ? $this->last_active_at->copy()->startOfDay() 
                : ($this->created_at ? $this->created_at->copy()->startOfDay() : Carbon::now()->startOfDay());
        } else {
            $refDate = Carbon::parse($lastHadir->tanggal)->startOfDay();
            if ($this->last_active_at && $this->last_active_at->gt($refDate)) {
                $refDate = $this->last_active_at->copy()->startOfDay();
            }
        }

        if ($this->reactivated_at && $this->reactivated_at->gt($refDate)) {
            $refDate = $this->reactivated_at->copy()->startOfDay();
        }

        $current = $refDate->copy();
        $today = Carbon::now()->startOfDay();
        
        $missedCount = 0;
        while ($current->lt($today)) {
            $current->addDay();
            $dayName = $current->format('l');
            if (in_array($dayName, $classDays)) {
                $missedCount++;
            }
        }

        return $missedCount;
    }

    /**
     * Cek apakah user tidak hadir latihan dalam 3 minggu berturut-turut.
     * Hitungan berdasarkan jumlah hari kelas yang terlewat (missed class days).
     */
    public function isTidakAktifKarenaAbsensi(): bool
    {
        if ($this->role === 'admin' || $this->role === 'pelatih') {
            return false;
        }

        $classDays = $this->getClassDays();
        if (empty($classDays)) {
            return false;
        }

        $daysPerWeek = count($classDays);
        $threshold = $daysPerWeek * 3; // 3 minggu kelas

        return $this->getMissedClassDaysCount() >= $threshold;
    }

    /**
     * Nonaktifkan akun di DB jika sudah 3 minggu tidak hadir.
     * Dipanggil oleh RoleMiddleware.
     */
    public function autoDeactivateIfInactive(): bool
    {
        if ($this->status === 'aktif' && $this->isTidakAktifKarenaAbsensi()) {
            $this->status = 'tidak aktif';
            $this->save();
            return true;
        }
        return false;
    }

    public function getKeaktifanStatusAttribute()
    {
        if ($this->role === 'admin') {
            return $this->status;
        }

        if ($this->status !== 'aktif') {
            return $this->status;
        }

        // Gunakan logika 3 minggu tanpa hadir
        if ($this->isTidakAktifKarenaAbsensi()) {
            return 'tidak aktif';
        }

        return 'aktif';
    }

    public function absensi()    { return $this->hasMany(Absensi::class); }
    public function pembayaran() { return $this->hasMany(Pembayaran::class); }
    public function materi()     { return $this->hasMany(Materi::class, 'pelatih_id'); }
    public function jadwals()    { return $this->hasMany(Jadwal::class, 'pelatih_id'); }
}
