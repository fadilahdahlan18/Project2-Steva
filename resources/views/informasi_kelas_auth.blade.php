@extends('template.main')
@section('title', 'Informasi Kelas & FAQ')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-circle-info"></i> Informasi Kelas & FAQ</h2>
    <div class="breadcrumb">
        <span>Bantuan</span>
        <i class="fa-solid fa-chevron-right" style="font-size:10px; margin: 0 4px; color: var(--gray-400);"></i>
        <span>FAQ</span>
    </div>
</div>

@include('informasi_kelas_content')
@endsection
