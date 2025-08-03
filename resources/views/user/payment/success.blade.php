@extends('layouts.user')

@section('title', 'Pembayaran Berhasil')

@section('content')
    <div class="text-center mt-5">
        <h2>Pembayaran berhasil!</h2>
        <p>Terima kasih, pesanan kamu sedang diproses.</p>
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
    </div>
@endsection
