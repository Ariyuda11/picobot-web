
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PICOBOT</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f3f7fb;
            color: #263445;
            overflow-x: hidden;
            position: relative;
        }

        /* BACKGROUND RUMAH SAKIT */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background-image: url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv7dpGmDHvtUq8eSPQwpkw3lEBof3Xmj_a71J8f8lsnQVS33U_6fVxbJLn&s=10");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.30;
            animation: backgroundMove 25s ease-in-out infinite alternate;
        }

        /* LAPISAN TRANSPARAN */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(243, 247, 251, 0.55);
            z-index: -1;
            pointer-events: none;
        }

        @keyframes backgroundMove {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.08);
            }
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            min-height: 75px;
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 7%;
            box-shadow: 0 3px 15px rgba(0,0,0,0.06);
            position: relative;
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #087eaa, #16b6bd);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 21px;
        }

        .brand-text h2 {
            font-size: 20px;
            font-weight: 800;
            color: #087eaa;
            letter-spacing: 1px;
        }

        .brand-text p {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
            letter-spacing: 1px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-info {
            text-align: right;
        }

        .user-info h4 {
            font-size: 13px;
            color: #263445;
        }

        .user-info p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        .logout-btn {
            background: #fff0f0;
            color: #dc4444;
            border: none;
            padding: 10px 15px;
            border-radius: 9px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #dc4444;
            color: white;
        }

        /* CONTAINER */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 45px 25px 120px;
            position: relative;
            z-index: 2;
        }

        /* WELCOME */
        .welcome {
            background: linear-gradient(
                120deg,
                rgba(5, 111, 157, 0.94),
                rgba(15, 174, 183, 0.90)
            );
            padding: 35px;
            border-radius: 18px;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 112, 160, 0.18);
            margin-bottom: 35px;
        }

        .welcome h1 {
            font-size: 27px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .welcome p {
            font-size: 13px;
            line-height: 1.8;
            max-width: 650px;
            color: rgba(255,255,255,0.9);
        }

        .welcome-label {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 10px;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        /* JUDUL MENU */
        .section-title {
            margin-bottom: 20px;
        }

        .section-title h2 {
            font-size: 19px;
            color: #263445;
            margin-bottom: 7px;
        }

        .section-title p {
            font-size: 12px;
            color: #64748b;
        }

        /* MENU GRID */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card {
            background: rgba(255,255,255,0.91);
            backdrop-filter: blur(10px);
            padding: 27px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: 0.3s;
            min-height: 190px;
        }

        .card:hover {
            transform: translateY(-7px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.10);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 18px;
        }

        .blue {
            background: #e0f2fe;
            color: #0284c7;
        }

        .green {
            background: #dcfce7;
            color: #16a34a;
        }

        .purple {
            background: #f3e8ff;
            color: #9333ea;
        }

        .card h3 {
            font-size: 15px;
            margin-bottom: 9px;
            color: #263445;
        }

        .card p {
            font-size: 12px;
            line-height: 1.7;
            color: #718096;
        }

        .card-arrow {
            margin-top: 17px;
            font-size: 12px;
            font-weight: 700;
            color: #087eaa;
        }

        /* AMBULANS BERGERAK */
        .ambulance-animation {
            position: fixed;
            bottom: 15px;
            left: -250px;
            z-index: 20;
            pointer-events: none;
            animation: ambulanceMove 18s linear infinite;
        }

        .ambulance-car {
            font-size: 65px;
            line-height: 1;

            /* MEMBALIK ARAH AMBULANS */
            transform: scaleX(-1);

            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.20));
        }

        .ambulance-label {
            background: white;
            color: #087eaa;
            font-size: 9px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 5px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            white-space: nowrap;
        }

        @keyframes ambulanceMove {
            0% {
                left: -250px;
            }
            100% {
                left: calc(100% + 250px);
            }
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 25px 15px;
            font-size: 11px;
            color: #64748b;
            position: relative;
            z-index: 2;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 13px 5%;
            }

            .welcome {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 21px;
            }

            .user-info {
                display: none;
            }

            .container {
                padding: 30px 18px 110px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            body::before,
            .ambulance-animation {
                animation: none;
            }

            .ambulance-animation {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="brand">
            <div class="brand-icon">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>

            <div class="brand-text">
                <h2>PICOBOT</h2>
                <p>REHABILITATION MONITORING SYSTEM</p>
            </div>
        </div>

        <div class="navbar-right">
            <div class="user-info">
                <h4>{{ Auth::user()->name }}</h4>
                <p>{{ ucfirst(Auth::user()->role) }}</p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- KONTEN UTAMA -->
    <main class="container">

        <!-- WELCOME -->
        <section class="welcome">
            <span class="welcome-label">
                <i class="fa-solid fa-hospital"></i>
                RSUD Dr. Soetomo Surabaya
            </span>

            <h1>
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>

            <p>
                PICOBOT merupakan sistem informasi untuk mendukung
                monitoring rehabilitasi pasien stroke. Sistem ini
                membantu pengelolaan data pasien serta pemantauan
                aktivitas rehabilitasi secara terintegrasi.
            </p>
        </section>

        <!-- MENU -->
        <div class="section-title">
            <h2>Menu Utama</h2>
            <p>Pilih menu untuk mengakses fitur sistem PICOBOT.</p>
        </div>

        <div class="menu-grid">

            <!-- DATA PASIEN -->
            <a href="{{ route('patients.index') }}" class="card-link">
                <div class="card">
                    <div class="card-icon blue">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>

                    <h3>Data Pasien</h3>

                    <p>
                        Mengelola informasi pasien, menambahkan data,
                        melihat detail, memperbarui, dan menghapus data pasien.
                    </p>

                    <div class="card-arrow">
                        Buka Menu <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </div>
            </a>

            <!-- RIWAYAT LATIHAN -->
            <div class="card">
                <div class="card-icon green">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>

                <h3>Riwayat Latihan</h3>

                <p>
                    Menyediakan informasi riwayat aktivitas latihan
                    rehabilitasi yang dilakukan oleh pasien.
                </p>

                <div class="card-arrow">
                    Segera Hadir
                </div>
            </div>

            <!-- MONITORING PROGRES -->
            <div class="card">
                <div class="card-icon purple">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <h3>Monitoring Progres</h3>

                <p>
                    Menampilkan perkembangan rehabilitasi pasien
                    berdasarkan aktivitas dan hasil latihan.
                </p>

                <div class="card-arrow">
                    Segera Hadir
                </div>
            </div>

        </div>
    </main>

    <!-- AMBULANS -->
    <div class="ambulance-animation">
        <div class="ambulance-label">
            RSUD DR. SOETOMO
        </div>

        <div class="ambulance-car">
            🚑
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        &copy; {{ date('Y') }} PICOBOT - RSUD Dr. Soetomo Surabaya.
        All Rights Reserved.
    </footer>

</body>
</html>
