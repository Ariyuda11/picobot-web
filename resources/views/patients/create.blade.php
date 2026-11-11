<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pasien | PICOBOT</title>

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
        }

        .breadcrumb a {
            color: #078b88;
            text-decoration: none;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .form-header {
            margin-bottom: 25px;
            padding-bottom: 18px;
            border-bottom: 1px solid #edf1f5;
        }

        .form-header h2 {
            font-size: 18px;
            color: #19344d;
            margin-bottom: 7px;
        }

        .form-header p {
            font-size: 12px;
            color: #8493a5;
            line-height: 1.6;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #425a70;
        }

        .required {
            color: #dc4545;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dfe7ee;
            border-radius: 8px;
            outline: none;
            color: #19344d;
            background: white;
            font-size: 12px;
            transition: .2s;
        }

        .form-control:focus {
            border-color: #087e8b;
            box-shadow: 0 0 0 3px rgba(8,126,139,.08);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-control.is-invalid {
            border-color: #dc4545;
        }

        .error-message {
            color: #dc4545;
            font-size: 11px;
        }

        .form-note {
            margin-top: 22px;
            padding: 13px 15px;
            background: #eaf7f7;
            border-radius: 8px;
            color: #347d80;
            font-size: 11px;
            line-height: 1.7;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf1f5;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 19px;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }

        .btn-cancel {
            background: #edf1f5;
            color: #647b8e;
        }

        .btn-cancel:hover {
            background: #dfe6ec;
        }

        .btn-submit {
            background: #087e8b;
            color: white;
        }

        .btn-submit:hover {
            background: #066b76;
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

            .form-card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }

            .welcome {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 21px;
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
        <h1>Tambah Data Pasien</h1>
        <p>
            Masukkan informasi identitas pasien untuk didaftarkan
            ke dalam sistem rehabilitasi PICOBOT.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('patients.index') }}">Data Pasien</a>
        <span>/</span>
        <span>Tambah Pasien</span>
    </div>

    <section class="form-card">

        <div class="form-header">
            <h2>Formulir Identitas Pasien</h2>
            <p>
                Lengkapi informasi pasien pada formulir berikut.
                Kolom bertanda <span class="required">*</span> wajib diisi.
            </p>
        </div>

        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <!-- NOMOR REKAM MEDIS -->
                <div class="form-group">
                    <label for="medical_record_number">
                        Nomor Rekam Medis <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="medical_record_number"
                        name="medical_record_number"
                        class="form-control @error('medical_record_number') is-invalid @enderror"
                        value="{{ old('medical_record_number') }}"
                        placeholder="Contoh: RM-2026-001"
                        maxlength="50"
                        required
                    >

                    @error('medical_record_number')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NAMA PASIEN -->
                <div class="form-group">
                    <label for="name">
                        Nama Lengkap Pasien <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap pasien"
                        maxlength="255"
                        required
                    >

                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- JENIS KELAMIN -->
                <div class="form-group">
                    <label for="gender">
                        Jenis Kelamin <span class="required">*</span>
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        class="form-control @error('gender') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Jenis Kelamin --</option>

                        <option value="Laki-laki"
                            {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki
                        </option>

                        <option value="Perempuan"
                            {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan
                        </option>
                    </select>

                    @error('gender')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TANGGAL LAHIR -->
                <div class="form-group">
                    <label for="birth_date">Tanggal Lahir</label>

                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        class="form-control @error('birth_date') is-invalid @enderror"
                        value="{{ old('birth_date') }}"
                        max="{{ date('Y-m-d') }}"
                    >

                    @error('birth_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NOMOR TELEPON -->
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890"
                        maxlength="20"
                    >

                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- ALAMAT -->
                <div class="form-group full">
                    <label for="address">Alamat Lengkap</label>

                    <textarea
                        id="address"
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        placeholder="Masukkan alamat lengkap pasien..."
                    >{{ old('address') }}</textarea>

                    @error('address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="form-note">
                Informasi pasien akan disimpan ke dalam database PICOBOT.
                Pastikan nomor rekam medis yang dimasukkan belum pernah digunakan.
            </div>

            <div class="form-actions">
                <a href="{{ route('patients.index') }}" class="btn btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn btn-submit">
                    Simpan Data Pasien
                </button>
            </div>

        </form>

    </section>

    <div class="footer">
        © 2026 PICOBOT - RSUD Dr. Soetomo Surabaya
    </div>

</main>

</body>
</html>
