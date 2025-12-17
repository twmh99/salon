@extends('layouts.app')

@section('content')
@php($title = 'Login Akun')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 auth-card overflow-hidden">
                    <div class="col-lg-6 p-5">
                        <h2 class="mb-3">Masuk Ke Ruang Reservasi</h2>
                        <p class="text-muted">Gunakan nomor HP (customer) atau username admin untuk menikmati dashboard personal.</p>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('customer.login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nomor HP / Username</label>
                                <input type="text" class="form-control @error('identifier') is-invalid @enderror" name="identifier" value="{{ old('identifier') }}" placeholder="Masukkan No. HP atau Username" required>
                                @error('identifier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Masukkan Password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-rose w-100">Masuk</button>
                        </form>
                        <p class="mt-3">Belum punya akun? <a href="{{ route('customer.register') }}">Daftar sekarang</a></p>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block auth-illustration" style="background-image:url('https://i.pinimg.com/736x/bf/1f/a7/bf1fa7dc7664cdb87ff151753c8f3ca1.jpg');">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
