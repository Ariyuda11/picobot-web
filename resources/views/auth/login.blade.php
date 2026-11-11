
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PICOBOT RSUD Dr. Soetomo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            overflow-x: hidden;
            background: linear-gradient(135deg, #071b36, #0d3155, #087e8b);
            position: relative;
        }

        /* Animated background */
        .background-circle {
            position: fixed;
            border-radius: 50%;
            opacity: .3;
            animation: float 9s ease-in-out infinite;
            pointer-events: none;
        }

        .circle-one {
            width: 350px;
            height: 350px;
            background: #21d4c2;
            top: -120px;
            left: -100px;
        }

        .circle-two {
            width: 280px;
            height: 280px;
            background: #4285ff;
            bottom: -100px;
            right: -70px;
            animation-delay: 2s;
        }

        .circle-three {
            width: 130px;
            height: 130px;
            background: #8be9e0;
            top: 65%;
            left: 12%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(-30px) translateX(20px);
            }
        }

        /* Main container */
        .login-container {
            width: 1050px;
            max-width: 100%;
            min-height: 620px;
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            background: rgba(255,255,255,.98);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,.3);
            position: relative;
            z-index: 2;
            animation: appear .8s ease;
        }

        @keyframes appear {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Left banner */
        .login-banner {
            background: linear-gradient(145deg, #0c3155, #087e8b);
            color: white;
            padding: 45px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-banner::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            top: 180px;
            left: -170px;
        }

        .login-banner::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 50%;
            bottom: -150px;
            right: -100px;
        }

        .brand {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: rgba(255,255,255,.17);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .brand h1 {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .brand small {
            display: block;
            color: #bcece9;
            font-size: 10px;
            letter-spacing: 2px;
            margin-top: 3px;
        }

        .banner-content {
            position: relative;
            z-index: 2;
            margin-top: 30px;
        }

        .banner-content h2 {
            font-size: 29px;
            line-height: 1.4;
            margin-bottom: 13px;
        }

        .banner-content p {
            color: #d1e9ee;
            font-size: 14px;
            line-height: 1.8;
            max-width: 390px;
        }

        /* Hospital logo */
        .hospital-logo-wrapper {
            height: 230px;
            margin-top: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hospital-logo {
            width: 220px;
            height: 220px;
            object-fit: contain;
            border-radius: 20px;
            padding: 10px;
            background: rgba(255,255,255,.96);
            box-shadow: 0 15px 35px rgba(0,0,0,.17);
            animation: logoFloat 4s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .hospital-name {
            position: relative;
            z-index: 2;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #d1e9ee;
            margin-top: 5px;
        }

        .banner-footer {
            position: relative;
            z-index: 2;
            font-size: 10px;
            color: #c5e6e9;
            margin-top: 20px;
        }

        /* Right login panel */
        .login-form {
            padding: 55px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-label-top {
            display: inline-block;
            background: #e5f8f6;
            color: #078b88;
            padding: 7px 13px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            width: fit-content;
            margin-bottom: 22px;
        }

        .login-form h2 {
            color: #173b5c;
            font-size: 30px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #8493a5;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #344b63;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #dce5ed;
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
            color: #243b53;
            outline: none;
            transition: .3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0dada6;
            background: white;
            box-shadow: 0 0 0 4px rgba(13,173,166,.10);
        }

        .form-group input::placeholder {
            color: #aab6c2;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 80px;
        }

        .toggle-password {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #078b88;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .login-button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(100deg, #087e8b, #12b6a9);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .3s;
            box-shadow: 0 8px 20px rgba(8,126,139,.2);
            margin-top: 5px;
        }

        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(8,126,139,.3);
        }

        .login-button:active {
            transform: scale(.98);
        }

        .error-message {
            padding: 12px;
            border-radius: 9px;
            background: #fff1f2;
            color: #dc2626;
            font-size: 12px;
            margin-bottom: 18px;
        }

        .form-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #edf1f5;
            text-align: center;
            color: #9aa7b5;
            font-size: 10px;
            line-height: 1.7;
        }

        /* Responsive */
        @media (max-width: 800px) {
            body {
                padding: 20px;
            }

            .login-container {
                grid-template-columns: 1fr;
                max-width: 480px;
            }

            .login-banner {
                padding: 30px;
            }

            .banner-content {
                margin-top: 35px;
            }

            .banner-content h2 {
                font-size: 23px;
            }

            .hospital-logo-wrapper {
                height: 180px;
            }

            .hospital-logo {
                width: 165px;
                height: 165px;
            }

            .login-form {
                padding: 40px 30px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="background-circle circle-one"></div>
    <div class="background-circle circle-two"></div>
    <div class="background-circle circle-three"></div>

    <main class="login-container">

        <!-- LEFT BANNER -->
        <section class="login-banner">

            <div class="brand">
                <div class="brand-logo">
                    <div class="logo-icon">✚</div>
                    <div>
                        <h1>PICOBOT</h1>
                        <small>REHABILITATION SYSTEM</small>
                    </div>
                </div>
            </div>

            <div class="banner-content">
                <h2>Pantau Perkembangan.<br>Dukung Pemulihan.</h2>

                <p>
                    Platform monitoring dan pencatatan
                    progres latihan terapi pasien untuk
                    mendukung evaluasi rehabilitasi.
                </p>
            </div>

            <div class="hospital-logo-wrapper">
                <img
                    class="hospital-logo"
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNwlueRav0X5cZWf3HqqbDQcNKOnfmETmJQ6fwzVkxMjLf1N4gJHZf6lCj&s=10"
                    alt="Logo RSUD Dr. Soetomo"
                >
            </div>

            <div class="hospital-name">
                RSUD Dr. Soetomo Surabaya
            </div>

            <div class="banner-footer">
                © 2026 PICOBOT. Rehabilitation Monitoring System.
            </div>

        </section>

        <!-- RIGHT LOGIN FORM -->
        <section class="login-form">

            <span class="form-label-top">
                AKSES PENGGUNA
            </span>

            <h2>Selamat Datang</h2>

            <p class="subtitle">
                Silakan masuk menggunakan akun Admin
                atau Pasien untuk mengakses sistem PICOBOT.
            </p>

            @if ($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="role">JENIS PENGGUNA</label>

                    <select id="role" name="role" required>
                        <option value="">Pilih jenis pengguna</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="pasien" {{ old('role') == 'pasien' ? 'selected' : '' }}>
                            Pasien
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="username">USERNAME</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            onclick="togglePassword()"
                            id="toggleButton"
                        >
                            LIHAT
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-button">
                    Masuk ke PICOBOT →
                </button>

            </form>

            <div class="form-footer">
                Sistem Monitoring Progres Latihan Terapi Stroke<br>
                PICOBOT Rehabilitation Monitoring System
            </div>

        </section>

    </main>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const button = document.getElementById('toggleButton');

            if (password.type === 'password') {
                password.type = 'text';
                button.textContent = 'SEMBUNYI';
            } else {
                password.type = 'password';
                button.textContent = 'LIHAT';
            }
        }
    </script>

</body>
</html>
