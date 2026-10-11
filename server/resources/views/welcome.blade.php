<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Beyond Finish Line</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0F3D22 0%, #333A12 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #FF6600, #FFBF00, #E2B34A);
        }

        .logo-section {
            margin-bottom: 40px;
        }

        .logo-placeholder {
            width: 160px;
            height: 160px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #163C1E, #000);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            color: #FFFFFF;
            box-shadow: 0 8px 20px rgba(226, 179, 74, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-placeholder:hover {
            transform: scale(1.05);
        }

        /* Replace the .logo-placeholder with an actual image if you have a logo */
        .logo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        h1 {
            color: #333A12;
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.2;
        }

        .subtitle {
            color: #997557;
            font-size: 1.1rem;
            margin-bottom: 50px;
            font-weight: 400;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #FF6600, #FFBF00);
            color: #FFFFFF;
            text-decoration: none;
            padding: 18px 60px;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 102, 0, 0.4);
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 102, 0, 0.5);
        }

        .cta-button:active {
            transform: translateY(-1px);
        }

        .decorative-line {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #FF6600, #E2B34A);
            margin: 30px auto;
            border-radius: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .logo-placeholder {
                width: 120px;
                height: 120px;
                font-size: 40px;
            }

            .cta-button {
                padding: 16px 50px;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.75rem;
            }

            .subtitle {
                font-size: 0.95rem;
                margin-bottom: 40px;
            }

            .logo-placeholder {
                width: 100px;
                height: 100px;
                font-size: 36px;
            }

            .cta-button {
                padding: 14px 40px;
                font-size: 1rem;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <div class="logo-placeholder">

                 <img src="{{ asset('images/logo3.png') }}" style="padding: 10px;" alt="Beyond Finish Line Logo">
                {{-- BFL --}}
            </div>
            <div class="decorative-line"></div>
        </div>

        <h1>Welcome to Admin</h1>
        <p class="subtitle">Beyond Finish Line</p>

        <a href="{{ route('admin.dashboard') }}" class="cta-button">Get Started</a>
    </div>
</body>
</html>
