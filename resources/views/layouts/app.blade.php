<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puskesmas Sehat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-green: #1b5e20;
            /* Hijau Tua */
            --light-green: #e8f5e9;
            /* Hijau Muda (Background) */
            --accent-green: #4caf50;
            /* Hijau Cerah (Tombol/Icon) */
            --white: #ffffff;
        }

        body {
            background-color: var(--light-green);
        }

        /* Navbar Custom */
        .navbar-custom {
            background-color: var(--primary-green) !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: var(--white) !important;
        }

        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }

        .card-header-custom {
            background-color: var(--primary-green);
            color: var(--white);
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        /* Buttons */
        .btn-hijau {
            background-color: var(--primary-green);
            color: var(--white);
        }

        .btn-hijau:hover {
            background-color: #144a19;
            color: var(--white);
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-custom">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    🏥 Puskesmas Sehat
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar Akun</a></li>
                        @else
                            @if (auth()->user()->role == 'admin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard
                                        Admin</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda Pasien</a></li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>
