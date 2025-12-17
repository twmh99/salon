@extends('layouts.app')

@section('content')
@php($title = 'Registrasi Customer')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 auth-card overflow-hidden">
                    <div class="col-lg-6 d-none d-lg-block auth-illustration" style="background-image:url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80');">
                    </div>
                    <div class="col-lg-6 p-5">
                        <h2 class="mb-3">Mulai Jadi Member DBeauty</h2>
                        <p class="text-muted mb-4">Reservasi lebih cepat, cek status real-time, dan nikmati promo spesial member.</p>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('customer.register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="tel" name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx" required>
                                @error('nomor_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="6" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-rose w-100">Daftar Sekarang</button>
                        </form>
                        <p class="mt-3 mb-0 text-center">Sudah punya akun? <a href="{{ route('customer.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
