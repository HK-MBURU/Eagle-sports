<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Beyond Finish Line</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bfl-gold: #E2B34A;
            --bfl-brown: #997557;
            --bfl-orange: #FF6600;
            --bfl-dark: #333A12;
            --bfl-yellow: #FFBF00;
            --bfl-green: #0F3D22;
        }

        body {
            background: linear-gradient(135deg, var(--bfl-green) 0%, var(--bfl-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            max-width: 480px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--bfl-gold);
            padding-top: 30px;
            text-align: center;
        }

        .logo-container {
            width: 140px;
            height: 140px;
            background: var(--bfl-green);
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            padding: 15px;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card-header-custom h3 {
            color: white;
            margin: 0 0 8px 0;
            font-weight: 700;
            font-size: 1.9rem;
        }

        .card-header-custom p {
            color: var(--bfl-gold);
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        .card-body-custom {
            padding: 45px 35px;
        }

        .form-label {
            color: var(--bfl-dark);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e5e5e5;
            border-radius: 10px;
            padding: 13px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--bfl-orange);
            box-shadow: 0 0 0 0.15rem rgba(255, 102, 0, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--bfl-orange), var(--bfl-yellow));
            border: none;
            border-radius: 10px;
            padding: 14px 30px;
            font-weight: 600;
            font-size: 1.05rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 102, 0, 0.35);
        }

        .btn-primary-custom:active {
            transform: translateY(0);
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #d0d0d0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--bfl-orange);
            border-color: var(--bfl-orange);
        }

        .form-check-input:focus {
            border-color: var(--bfl-orange);
            box-shadow: 0 0 0 0.15rem rgba(255, 102, 0, 0.1);
        }

        .form-check-label {
            color: #555;
            font-size: 0.9rem;
            cursor: pointer;
            margin-left: 5px;
        }

        .forgot-link {
            color: var(--bfl-brown);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--bfl-orange);
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            padding: 14px 16px;
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .invalid-feedback {
            font-size: 0.875rem;
            margin-top: 6px;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.1);
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 0;
            }

            .card-header-custom {
                padding: 40px 25px;
            }

            .logo-container {
                width: 120px;
                height: 120px;
                padding: 12px;
            }

            .card-header-custom h3 {
                font-size: 1.65rem;
            }

            .card-body-custom {
                padding: 35px 25px;
            }

            .btn-primary-custom {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header with Logo -->
            <div class="card-header-custom">
                <div class="logo-container">
                    <img src="{{ asset('images/logo4.png') }}" alt="Beyond Finish Line Logo">
                </div>
                <h3>Welcome Back</h3>

            </div>

            <!-- Body -->
            <div class="card-body-custom">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-custom mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
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
                            placeholder="your.email@example.com">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
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
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="remember_me"
                                name="remember">
                            <label class="form-check-label" for="remember_me">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary-custom">
                        Log In
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
