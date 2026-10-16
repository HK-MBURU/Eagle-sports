<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <title>Eagle Sports — Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #10284a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e7ef;
            max-width: 420px;
            width: 100%;
            overflow: hidden;
        }

        /* Red top stripe */
        .card::before {
            content: '';
            display: block;
            height: 4px;
            background: #d62835;
        }

        .card-inner {
            padding: 48px 40px 44px;
            text-align: center;
        }

        .logo-wrap {
            width: 90px;
            height: 90px;
            margin: 0 auto 22px;
            border-radius: 10px;
            background: #f4f6f9;
            border: 1px solid #e2e7ef;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }

        h1 {
            color: #10284a;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -.4px;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #667085;
            font-size: 0.82rem;
            margin-bottom: 36px;
        }

        .cta-btn {
            display: inline-block;
            background: #d62835;
            color: #fff;
            text-decoration: none;
            padding: 12px 40px;
            border-radius: 7px;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            transition: background .15s;
        }

        .cta-btn:hover { background: #b51e2a; }

        .footer-note {
            margin-top: 32px;
            font-size: 0.72rem;
            color: #aab0bc;
        }

        @media (max-width: 480px) {
            .card-inner { padding: 36px 28px 32px; }
            h1 { font-size: 1.3rem; }
            .cta-btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-inner">
            <div class="logo-wrap">
                <img src="{{ asset('images/es-logo.png') }}" alt="Eagle Sports Logo">
            </div>

            <h1>Eagle Sports</h1>
            <p class="subtitle">Administration Portal</p>

            <a href="{{ route('admin.dashboard') }}" class="cta-btn">Get Started</a>

            <p class="footer-note">Restricted access &mdash; authorised personnel only</p>
        </div>
    </div>
</body>
</html>
