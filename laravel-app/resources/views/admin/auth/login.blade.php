@extends('layouts.app')

@section('content')
@php($title = 'Login Admin')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="row g-0 auth-card overflow-hidden">
                    <div class="col-lg-6 p-5">
                        <h2 class="mb-3">Admin Access</h2>
                        <p class="text-muted">Pantau jadwal, verifikasi reservasi, dan kelola team therapist.</p>
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-rose w-100">Masuk Dashboard</button>
                        </form>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block auth-illustration" style="background-image:url('https://images.unsplash.com/photo-1526256262350-7da7584cf5eb?auto=format&fit=crop&w=900&q=80');">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
