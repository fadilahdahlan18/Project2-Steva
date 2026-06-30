@extends('template.main')
@section('title', 'Jadwal Latihan')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-calendar-days"></i> Jadwal Latihan</h2>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
    @forelse($jadwal as $j)
    <div class="card" style="transition:transform 0.22s,box-shadow 0.22s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(128,0,32,0.15)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="padding:0;">
            <div style="background:linear-gradient(135deg,#800020,#a0002a);padding:20px;border-radius:10px 10px 0 0;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="width:48px;height:48px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-music" style="color:#f0d080;font-size:20px;"></i>
                    </div>
                    <span style="background:rgba(255,255,255,0.2);color:#fff;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                        {{ $j->hari }}
                    </span>
                </div>
                <div style="color:#fff;font-size:16px;font-weight:700;margin-top:14px;">{{ $j->nama_kelas }}</div>
            </div>
            <div style="padding:16px;display:flex;align-items:center;gap:8px;">
                <i class="fa-regular fa-clock" style="color:#800020;"></i>
                <span style="font-size:14px;font-weight:600;">{{ $j->jam }} WIB</span>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;padding:40px;text-align:center;color:#adb5bd;">
        <i class="fa-solid fa-calendar-xmark" style="font-size:36px;margin-bottom:12px;display:block;"></i>
        Belum ada jadwal latihan.
    </div>
    @endforelse
</div>
@endsection
