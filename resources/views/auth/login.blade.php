@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-4 text-center">
                        <h4 class="fw-bold">{{ __('Login') }}</h4>
                        <p class="text-muted small">Silakan masuk dengan Email (Admin) atau NIK (Pasien)</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="login" class="form-label text-secondary small fw-bold">Email atau NIK</label>
                                <input id="login" type="text"
                                    class="form-control form-control-lg @error('login') is-invalid @enderror" name="login"
                                    value="{{ old('login') }}" placeholder="Masukkan Email atau NIK" required autofocus>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                    class="form-label text-secondary small fw-bold">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" placeholder="Masukkan password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="remember">
                                        {{ __('Ingat Saya') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Lupa Password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid gap-2"> <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    {{ __('Masuk ke Akun') }}
                                </button>
                            </div>

                            @if (Route::has('register'))
                                <div class="text-center mt-4">
                                    <p class="small text-muted">Belum punya akun?
                                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar
                                            Sekarang</a>
                                    </p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
