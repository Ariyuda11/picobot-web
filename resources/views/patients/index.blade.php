
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien | PICOBOT</title>

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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 25px;
        }

        .welcome {
            background: linear-gradient(110deg, #0c3155, #087e8b);
            color: white;
            padding: 30px 35px;
            border-radius: 18px;
            margin-bottom: 28px;
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

        .section-title {
            font-size: 18px;
            margin-bottom: 17px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 21px;
            border-radius: 14px;
            border: 1px solid #edf1f5;
            box-shadow: 0 5px 20px rgba(0,0,0,.035);
        }

        .stat-card small {
            color: #8493a5;
            font-size: 12px;
        }

        .stat-card h2 {
            color: #123c5a;
            font-size: 27px;
            margin-top: 10px;
        }

        .stat-icon {
            color: #078b88;
            font-size: 20px;
            margin-bottom: 12px;
        }

        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
            border: 1px solid #edf1f5;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .card-header h3 {
            font-size: 16px;
        }

        .card-header p {
            color: #8493a5;
            font-size: 12px;
            margin-top: 6px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary {
            background: #087e8b;
            color: white;
            padding: 12px 17px;
            font-size: 12px;
        }

        .btn-primary:hover {
            background: #066b76;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            width: 310px;
            max-width: 100%;
            padding: 11px 14px;
            border: 1px solid #e1e8ef;
            border-radius: 8px;
            font-size: 12px;
            outline: none;
        }

        .search-box:focus {
            border-color: #087e8b;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 750px;
            border-collapse: collapse;
        }

        thead {
            background: #f3f7fb;
        }

        th {
            text-align: left;
            padding: 14px 12px;
            color: #647b8e;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf1f5;
            font-size: 12px;
        }

        tbody tr:hover {
            background: #f8fbfd;
        }

        .gender {
            display: inline-block;
            padding: 6px 10px;
            background: #e5f8f6;
            color: #078b88;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-detail {
            background: #e8f3fa;
            color: #28749b;
        }

        .btn-edit {
            background: #fff4df;
            color: #a87415;
        }

        .btn-delete {
            background: #fff0f0;
            color: #dc4545;
        }

        .btn-detail:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            filter: brightness(.96);
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .alert-success {
            background: #e5f8ef;
            color: #218653;
        }

        .empty {
            text-align: center;
            padding: 40px 15px;
            color: #8493a5;
        }

        .pagination {
            margin-top: 20px;
            font-size: 12px;
        }

        .footer {
            margin-top: 45px;
            padding-bottom: 20px;
            text-align: center;
            color: #9aa7b5;
            font-size: 11px;
        }

        @media(max-width: 700px) {
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

            .welcome {
                padding: 25px;
            }

            .welcome h1 {
                font-size: 21px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .content-card {
                padding: 18px;
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
        <h1>Pengelolaan Data Pasien</h1>
        <p>
            Kelola informasi dan identitas pasien yang mengikuti
            program rehabilitasi terapi menggunakan PICOBOT.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <span>Data Pasien</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2 class="section-title">Ringkasan Data Pasien</h2>

    <section class="stats">
        <div class="stat-card">
            <div class="stat-icon">♙</div>
            <small>Total Pasien Terdaftar</small>
            <h2>{{ $patients->total() }}</h2>
        </div>

        <div class="stat-card">
            <div class="stat-icon">▤</div>
            <small>Data Ditampilkan</small>
            <h2>{{ $patients->count() }}</h2>
        </div>

        <div class="stat-card">
            <div class="stat-icon">▣</div>
            <small>Status Pengelolaan</small>
            <h2 style="font-size: 20px;">Aktif</h2>
        </div>
    </section>

    <section class="content-card">

        <div class="card-header">
            <div>
                <h3>Daftar Pasien</h3>
                <p>Informasi identitas pasien PICOBOT.</p>
            </div>

            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                + Tambah Pasien
            </a>
        </div>

        <div class="toolbar">
            <input
                type="text"
                id="searchPatient"
                class="search-box"
                placeholder="Cari nama atau nomor rekam medis..."
            >
        </div>

        <div class="table-wrapper">
            <table id="patientTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Rekam Medis</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($patients as $index => $patient)
                        <tr>
                            <td>{{ $patients->firstItem() + $index }}</td>
                            <td>{{ $patient->medical_record_number }}</td>
                            <td>{{ $patient->name }}</td>
                            <td>
                                <span class="gender">
                                    {{ $patient->gender }}
                                </span>
                            </td>
                            <td>
                                {{ $patient->birth_date
                                    ? $patient->birth_date->format('d/m/Y')
                                    : '-' }}
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('patients.show', $patient->id) }}"
                                       class="btn btn-detail">Detail</a>

                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                       class="btn btn-edit">Edit</a>

                                    <form action="{{ route('patients.destroy', $patient->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data pasien ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada data pasien yang terdaftar.
                                <br><br>
                                Silakan klik tombol Tambah Pasien untuk memasukkan data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $patients->links() }}
        </div>

    </section>

    <div class="footer">
        © 2026 PICOBOT - RSUD Dr. Soetomo Surabaya
    </div>

</main>

<script>
    const searchInput = document.getElementById('searchPatient');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('#patientTable tbody tr');

        rows.forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword)
                ? ''
                : 'none';
        });
    });
</script>

</body>
</html>
