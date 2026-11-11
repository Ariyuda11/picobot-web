
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pasien | PICOBOT</title>

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
            background: #f3f7fb;
            color: #19344d;
        }

        .navbar {
            min-height: 75px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 5%;
            box-shadow: 0 2px 15px rgba(0,0,0,.05);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #087e8b;
            color: white;
            display: grid;
            place-items: center;
            font-size: 23px;
        }

        .brand h2 {
            font-size: 20px;
            font-weight: 800;
            color: #123c5a;
        }

        .brand small {
            display: block;
            color: #8193a5;
            font-size: 10px;
            letter-spacing: 1px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name {
            text-align: right;
            font-size: 13px;
            font-weight: 700;
        }

        .user-name small {
            display: block;
            color: #078b88;
            font-size: 11px;
            margin-top: 4px;
        }

        .logout-button {
            border: none;
            border-radius: 8px;
            padding: 10px 17px;
            background: #fff0f0;
            color: #dc4545;
            font-weight: 600;
            cursor: pointer;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 25px;
        }

        .welcome {
            background: linear-gradient(110deg, #0c3155, #087e8b);
            color: white;
            padding: 30px 35px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 12px 30px rgba(8,126,139,.15);
        }

        .welcome h1 {
            font-size: 25px;
            margin-bottom: 9px;
        }

        .welcome p {
            color: #d5eeee;
            font-size: 13px;
            line-height: 1.7;
        }

        .breadcrumb {
            display: flex;
            gap: 9px;
            align-items: center;
            font-size: 12px;
            color: #8999a9;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: #078b88;
            text-decoration: none;
        }

        .detail-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            padding-bottom: 20px;
            border-bottom: 1px solid #edf1f5;
            margin-bottom: 25px;
        }

        .detail-header h2 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .detail-header p {
            color: #8493a5;
            font-size: 12px;
        }

        .status {
            background: #e5f8f6;
            color: #078b88;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .detail-item {
            padding: 17px;
            background: #f8fafc;
            border: 1px solid #edf1f5;
            border-radius: 10px;
        }

        .detail-item.full {
            grid-column: 1 / -1;
        }

        .detail-item small {
            display: block;
            color: #8493a5;
            font-size: 11px;
            margin-bottom: 9px;
        }

        .detail-item h3 {
            font-size: 13px;
            font-weight: 600;
            color: #19344d;
            overflow-wrap: anywhere;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf1f5;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 17px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-back {
            background: #edf1f5;
            color: #647b8e;
        }

        .btn-edit {
            background: #087e8b;
            color: white;
        }

        .footer {
            margin-top: 40px;
            padding-bottom: 20px;
            text-align: center;
            color: #9aa7b5;
            font-size: 11px;
        }

        @media(max-width: 650px) {
            .navbar {
                padding: 12px 18px;
            }

            .user-name {
                display: none;
            }

            .container {
                margin-top: 25px;
                padding: 0 15px;
            }

            .detail-card {
                padding: 20px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-item.full {
                grid-column: auto;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="brand">
        <div class="brand-icon">✚</div>
        <div>
            <h2>PICOBOT</h2>
            <small>REHABILITATION SYSTEM</small>
        </div>
    </div>

    <div class="user-info">
        <div class="user-name">
            {{ Auth::user()->name }}
            <small>{{ strtoupper(Auth::user()->role) }}</small>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Keluar</button>
        </form>
    </div>
</nav>

<main class="container">

    <section class="welcome">
        <h1>Detail Data Pasien</h1>
        <p>
            Informasi lengkap identitas pasien yang terdaftar
            dalam sistem rehabilitasi PICOBOT.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('patients.index') }}">Data Pasien</a>
        <span>/</span>
        <span>Detail Pasien</span>
    </div>

    <section class="detail-card">

        <div class="detail-header">
            <div>
                <h2>Informasi Identitas Pasien</h2>
                <p>Nomor Rekam Medis: {{ $patient->medical_record_number }}</p>
            </div>

            <span class="status">Terdaftar</span>
        </div>

        <div class="detail-grid">

            <div class="detail-item">
                <small>Nomor Rekam Medis</small>
                <h3>{{ $patient->medical_record_number }}</h3>
            </div>

            <div class="detail-item">
                <small>Nama Lengkap</small>
                <h3>{{ $patient->name }}</h3>
            </div>

            <div class="detail-item">
                <small>Jenis Kelamin</small>
                <h3>{{ $patient->gender }}</h3>
            </div>

            <div class="detail-item">
                <small>Tanggal Lahir</small>
                <h3>
                    {{ $patient->birth_date
                        ? $patient->birth_date->format('d F Y')
                        : '-' }}
                </h3>
            </div>

            <div class="detail-item">
                <small>Nomor Telepon</small>
                <h3>{{ $patient->phone ?: '-' }}</h3>
            </div>

            <div class="detail-item full">
                <small>Alamat Lengkap</small>
                <h3>{{ $patient->address ?: '-' }}</h3>
            </div>

            <div class="detail-item">
                <small>Tanggal Data Dibuat</small>
                <h3>{{ $patient->created_at->format('d F Y, H:i') }}</h3>
            </div>

            <div class="detail-item">
                <small>Terakhir Diperbarui</small>
                <h3>{{ $patient->updated_at->format('d F Y, H:i') }}</h3>
            </div>

        </div>

        <div class="actions">
            <a href="{{ route('patients.index') }}" class="btn btn-back">
                Kembali
            </a>

            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-edit">
                Edit Data Pasien
            </a>
        </div>

    </section>

    <div class="footer">
        © 2026 PICOBOT - RSUD Dr. Soetomo Surabaya
    </div>

</main>

</body>
</html>
