<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role  = $request->get('role', 'murid');
        $kategori = $request->get('kategori_kelas');
        $jenis = $request->get('jenis_kelas');
        $status = $request->get('status');

        $users = User::with('jadwals')->where('role', $role)
            ->when($kategori, function($q) use ($kategori) {
                $q->where('kategori_kelas', $kategori);
            })
            ->when($jenis, function($q) use ($jenis) {
                $q->where('jenis_kelas', 'like', "%$jenis%");
            })
            ->when($status, function($q) use ($status, $role) {
                if ($role === 'pelatih') {
                    $q->where('status', $status);
                } else {
                    if ($status === 'aktif') {
                        $q->where('status', 'aktif')
                          ->where(function($q2) {
                              $q2->where(function($q3) {
                                  $q3->where('jenis_kelas', 'like', '%rampak%')
                                     ->where('jenis_kelas', 'not like', '%reguler%')
                                     ->where(function($q4) {
                                         $q4->whereRaw('COALESCE(last_active_at, created_at) >= ?', [now()->subWeeks(14)->startOfDay()]);
                                     });
                              })->orWhere(function($q3) {
                                  $q3->where('jenis_kelas', 'like', '%reguler%')
                                     ->where('jenis_kelas', 'not like', '%rampak%')
                                     ->where(function($q4) {
                                         $q4->whereRaw('COALESCE(last_active_at, created_at) >= ?', [now()->subWeeks(7)->startOfDay()]);
                                     });
                              })->orWhere(function($q3) {
                                  $q3->where('jenis_kelas', 'like', '%rampak%')
                                     ->where('jenis_kelas', 'like', '%reguler%')
                                     ->where(function($q4) {
                                         $q4->whereRaw('COALESCE(last_active_at, created_at) >= ?', [now()->subDays(33)->startOfDay()]);
                                     });
                              })->orWhere(function($q3) {
                                  $q3->whereNull('jenis_kelas')
                                     ->orWhere('jenis_kelas', '');
                              });
                          });
                    } elseif ($status === 'tidak aktif') {
                        $q->where(function($q2) {
                            $q2->where('status', 'tidak aktif')
                              ->orWhere(function($q3) {
                                  $q3->where('status', 'aktif')
                                     ->where(function($q4) {
                                         $q4->where(function($q5) {
                                             $q5->where('jenis_kelas', 'like', '%rampak%')
                                                ->where('jenis_kelas', 'not like', '%reguler%')
                                                ->whereRaw('COALESCE(last_active_at, created_at) < ?', [now()->subWeeks(14)->startOfDay()]);
                                         })->orWhere(function($q5) {
                                             $q5->where('jenis_kelas', 'like', '%reguler%')
                                                ->where('jenis_kelas', 'not like', '%rampak%')
                                                ->whereRaw('COALESCE(last_active_at, created_at) < ?', [now()->subWeeks(7)->startOfDay()]);
                                         })->orWhere(function($q5) {
                                             $q5->where('jenis_kelas', 'like', '%rampak%')
                                                ->where('jenis_kelas', 'like', '%reguler%')
                                                ->whereRaw('COALESCE(last_active_at, created_at) < ?', [now()->subDays(33)->startOfDay()]);
                                         });
                                     });
                              });
                        });
                    } else {
                        $q->where('status', $status);
                    }
                }
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.users.index', compact('users', 'role', 'kategori', 'jenis', 'status'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username|regex:/^\S+$/',
            'email'    => 'nullable|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:pelatih,murid',
            'status'   => 'nullable|in:aktif,tidak aktif,pending,ditolak',
            'no_hp'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->role === 'murid' || $request->role === 'pelatih') {
            $rules['kategori_kelas'] = 'required|in:pemula,madya,ahli';
            $rules['jenis_kelas'] = 'required|array';
        }

        $request->validate($rules, [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.regex'    => 'Username tidak boleh mengandung spasi.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoPath = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $userData = [
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status ?? 'aktif',
            'no_hp'    => $request->no_hp,
            'foto'     => $fotoPath,
        ];

        if ($request->role === 'pelatih') {
            // Consistency with AuthController: Generate unique kode_pelatih
            $lastPelatih = User::where('role', 'pelatih')->orderBy('id', 'desc')->first();
            $nextId = $lastPelatih ? $lastPelatih->id + 1 : 1;
            $userData['kode_pelatih'] = 'PLT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }

        if ($request->role === 'murid' || $request->role === 'pelatih') {
            $userData['kategori_kelas'] = $request->kategori_kelas;
            $userData['jenis_kelas'] = implode(',', $request->jenis_kelas);
        }

        User::create($userData);

        return redirect()->route('admin.users', ['role' => $request->role])
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|regex:/^\S+$/|unique:users,username,' . $id,
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'role'     => 'required|in:pelatih,murid',
            'status'   => 'required|in:aktif,tidak aktif,pending,ditolak',
            'no_hp'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->role === 'murid' || $request->role === 'pelatih') {
            $rules['kategori_kelas'] = 'required|in:pemula,madya,ahli';
            $rules['jenis_kelas'] = 'required|array';
        }

        $request->validate($rules, [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah digunakan.',
            'username.regex'    => 'Username tidak boleh mengandung spasi.',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && !str_starts_with($user->foto, 'data:')) {
                Storage::disk('public')->delete($user->foto);
            }
            $file = $request->file('foto');
            $user->foto = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath()));
        }

        $user->nama     = $request->nama;
        $user->username = $request->username;
        $user->email    = $request->email;
        $user->role     = $request->role;
        
        if ($request->status === 'aktif' && $user->status !== 'aktif') {
            $user->reactivated_at = now();
            $user->last_active_at = now();
        }
        
        $user->status   = $request->status;
        $user->no_hp    = $request->no_hp;

        if ($user->role === 'murid' || $user->role === 'pelatih') {
            $user->kategori_kelas = $request->kategori_kelas;
            $user->jenis_kelas = implode(',', $request->jenis_kelas);
        } else {
            $user->kategori_kelas = null;
            $user->jenis_kelas = null;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'pelatih' && $user->status === 'aktif') {
            $jenisKelasArray = explode(',', $user->jenis_kelas);
            \App\Models\Jadwal::where('kategori_kelas', $user->kategori_kelas)
                ->whereIn('jenis_kelas', $jenisKelasArray)
                ->update(['pelatih_id' => $user->id]);
        }

        return redirect()->route('admin.users', ['role' => $user->role])
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        if ($user->foto && !str_starts_with($user->foto, 'data:')) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();

        return redirect()->route('admin.users', ['role' => $role])
            ->with('success', 'Data pengguna berhasil dihapus.');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'aktif';
        $user->reactivated_at = now();
        $user->last_active_at = now();
        $user->save();

        if ($user->role === 'pelatih') {
            $jenisKelasArray = explode(',', $user->jenis_kelas);
            \App\Models\Jadwal::where('kategori_kelas', $user->kategori_kelas)
                ->whereIn('jenis_kelas', $jenisKelasArray)
                ->update(['pelatih_id' => $user->id]);
        }

        return redirect()->route('admin.users', ['role' => $user->role, 'status' => 'aktif'])->with('success', 'Akun ' . $user->nama . ' berhasil disetujui.');
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'ditolak';
        $user->save();

        return redirect()->back()->with('success', 'Akun ' . $user->nama . ' telah ditolak.');
    }
}
