<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>Login — Eagle Sports Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --es-navy:  #10284a;
            --es-red:   #d62835;
            --es-line:  #e2e7ef;
            --es-paper: #f4f6f9;
            --es-muted: #667085;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--es-navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
        }

        /* Card */
        .login-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--es-line);
            overflow: hidden;
        }

        /* Top stripe */
        .card-top {
            background: var(--es-navy);
            padding: 32px 36px 28px;
            text-align: center;
            border-bottom: 3px solid var(--es-red);
        }

        .logo-wrap {
            width: 80px;
            height: 80px;
            margin: 0 auto 18px;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card-top h2 {
            color: #fff;
            margin: 0 0 4px;
            font-size: 1.35rem;
            font-weight: 900;
            letter-spacing: -.3px;
        }

        .card-top p {
            color: rgba(255,255,255,.55);
            margin: 0;
            font-size: 0.8rem;
        }

        /* Body */
        .card-body-inner {
            padding: 32px 36px 36px;
        }

        .form-label {
            color: var(--es-navy);
            font-weight: 700;
            font-size: 0.82rem;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .form-control {
            border: 1px solid var(--es-line);
            border-radius: 7px;
            padding: 11px 14px;
            font-size: 0.95rem;
            color: #1a1a1a;
            background: var(--es-paper);
            transition: border-color .15s;
        }

        .form-control:focus {
            border-color: var(--es-red);
            box-shadow: 0 0 0 3px rgba(214,40,53,.08);
            background: #fff;
            outline: none;
        }

        .form-control::placeholder { color: #aab0bc; }

        .btn-login {
            background: var(--es-red);
            border: none;
            border-radius: 7px;
            padding: 12px 0;
            font-weight: 800;
            font-size: 0.9rem;
            color: #fff;
            width: 100%;
            cursor: pointer;
            letter-spacing: .5px;
            text-transform: uppercase;
            transition: background .15s;
            margin-top: 6px;
        }

        .btn-login:hover { background: #b51e2a; }
        .btn-login:active { background: #a01a25; }

        .form-check-input {
            width: 16px;
            height: 16px;
            border: 1px solid var(--es-line);
            cursor: pointer;
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--es-red);
            border-color: var(--es-red);
        }

        .form-check-input:focus {
            border-color: var(--es-red);
            box-shadow: 0 0 0 3px rgba(214,40,53,.08);
        }

        .form-check-label {
            color: var(--es-muted);
            font-size: 0.82rem;
            cursor: pointer;
            margin-left: 4px;
        }

        .forgot-link {
            color: var(--es-muted);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            transition: color .15s;
        }

        .forgot-link:hover { color: var(--es-red); }

        .alert-status {
            border-radius: 7px;
            border: 1px solid #c3e6cb;
            padding: 12px 14px;
            background: #f0fff4;
            color: #1a6b35;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
            color: var(--es-red);
        }

        .is-invalid {
            border-color: var(--es-red) !important;
            background: #fff;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(214,40,53,.08) !important;
        }

        /* Footer note */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.75rem;
            color: rgba(255,255,255,.35);
        }

        @media (max-width: 480px) {
            .card-top { padding: 26px 24px 22px; }
            .card-body-inner { padding: 26px 24px 30px; }
        }
    </style>
</head>

<body>
    <div class="login-wrap">
        <div class="login-card">

            <!-- Header -->
            <div class="card-top">
                <div class="logo-wrap">
                    <img src="{{ asset('images/es-logo.png') }}" alt="Eagle Sports Logo">
                </div>
                <h2>Eagle Sports</h2>
                <p>Administration Portal</p>
            </div>

            <!-- Form -->
            <div class="card-body-inner">

                @if (session('status'))
                    <div class="alert-status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@eaglesports.co.ke">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">Sign In</button>
                </form>

            </div>
        </div>

        <p class="login-footer">Eagle Sports &copy; {{ date('Y') }} — Restricted Access</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
