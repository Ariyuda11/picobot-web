@extends('layouts.picobot')

@section('title', 'Riwayat Latihan Saya | PICOBOT')

@section('content')

    <section class="welcome">
        <h1>Riwayat Latihan Saya</h1>
        <p>
            Pantau aktivitas latihan rehabilitasi yang telah Anda lakukan.
            Catat setiap latihan agar progres pemulihan dapat dipantau
            oleh tenaga medis.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <span>Riwayat Latihan Saya</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(! $patient)
        <section class="content-card">
            <div class="empty">
                Akun Anda belum terhubung dengan data pasien.
                <br>
                Silakan hubungi admin untuk menghubungkan akun dengan data pasien.
            </div>
        </section>
    @else

        <h2 class="section-title">Ringkasan Latihan Saya</h2>

        <section class="stats">
            <div class="stat-card">
                <small>Total Latihan Tercatat</small>
                <h2>{{ $stats['total'] }}</h2>
            </div>

            <div class="stat-card">
                <small>Latihan Hari Ini</small>
                <h2>{{ $stats['today'] }}</h2>
            </div>

            <div class="stat-card">
                <small>Rata-rata Durasi Latihan</small>
                <h2 style="font-size: 20px;">{{ $stats['average_duration'] }} menit</h2>
            </div>
        </section>

        <section class="content-card">

            <div class="card-header">
                <div>
                    <h3>Daftar Riwayat Latihan Saya</h3>
                    <p>
                        Pasien: {{ $patient->name }}
                        ({{ $patient->medical_record_number }})
                    </p>
                </div>

                <a href="{{ route('my-exercise-histories.create') }}" class="btn btn-primary">
                    + Catat Latihan
                </a>
            </div>

            <form action="{{ route('my-exercise-histories.index') }}" method="GET" class="toolbar">
                <input
                    type="text"
                    name="search"
                    class="search-box"
                    value="{{ $search }}"
                    placeholder="Cari jenis latihan..."
                >

                <button type="submit" class="btn btn-detail">Cari</button>
            </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Latihan</th>
                            <th>Jenis Latihan</th>
                            <th>Durasi</th>
                            <th>Intensitas</th>
                            <th>Nyeri</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($histories as $index => $history)
                            @php
                                $badgeClass = match ($history->intensity) {
                                    'Ringan' => 'badge-ringan',
                                    'Berat' => 'badge-berat',
                                    default => 'badge-sedang',
                                };
                            @endphp

                            <tr>
                                <td>{{ $histories->firstItem() + $index }}</td>

                                <td>
                                    {{ $history->performed_at->format('d/m/Y') }}
                                    <br>
                                    <span style="color: #8493a5; font-size: 11px;">
                                        {{ $history->performed_at->format('H:i') }} WIB
                                    </span>
                                </td>

                                <td>{{ $history->exercise_name }}</td>

                                <td>{{ $history->duration_minutes }} menit</td>

                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $history->intensity }}
                                    </span>
                                </td>

                                <td>
                                    {{ $history->pain_level !== null ? $history->pain_level . ' / 10' : '-' }}
                                </td>

                                <td>
                                    <a href="{{ route('my-exercise-histories.show', $history->id) }}"
                                       class="btn btn-detail">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty">
                                    Belum ada riwayat latihan yang tercatat.
                                    <br>
                                    Silakan klik tombol Catat Latihan untuk menambahkan data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $histories->links() }}
            </div>

        </section>
    @endif

@endsection
