@extends('layouts.picobot')

@section('title', 'Riwayat Latihan | PICOBOT')

@section('content')

    <section class="welcome">
        <h1>Riwayat Latihan Rehabilitasi</h1>
        <p>
            Kelola seluruh catatan aktivitas latihan rehabilitasi pasien,
            mulai dari jenis latihan, durasi, intensitas, hingga tingkat nyeri
            yang dirasakan setelah latihan.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <span>Riwayat Latihan</span>
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

    <h2 class="section-title">Ringkasan Riwayat Latihan</h2>

    <section class="stats">
        <div class="stat-card">
            <small>Total Riwayat Latihan</small>
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
                <h3>Daftar Riwayat Latihan</h3>
                <p>Seluruh aktivitas rehabilitasi yang tercatat pada sistem.</p>
            </div>

            <a href="{{ route('exercise-histories.create') }}" class="btn btn-primary">
                + Tambah Riwayat
            </a>
        </div>

        <form action="{{ route('exercise-histories.index') }}" method="GET" class="toolbar">
            <input
                type="text"
                name="search"
                class="search-box"
                value="{{ $search }}"
                placeholder="Cari nama pasien, no. rekam medis, atau latihan..."
            >

            <button type="submit" class="btn btn-detail">Cari</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Latihan</th>
                        <th>Pasien</th>
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

                            <td>
                                {{ $history->patient->name ?? '-' }}
                                <br>
                                <span style="color: #8493a5; font-size: 11px;">
                                    {{ $history->patient->medical_record_number ?? '-' }}
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
                                <div class="actions">
                                    <a href="{{ route('exercise-histories.show', $history->id) }}"
                                       class="btn btn-detail">Detail</a>

                                    <a href="{{ route('exercise-histories.edit', $history->id) }}"
                                       class="btn btn-edit">Edit</a>

                                    <form action="{{ route('exercise-histories.destroy', $history->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus riwayat latihan ini?')">
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
                            <td colspan="8" class="empty">
                                Belum ada riwayat latihan yang tercatat.
                                <br>
                                Silakan klik tombol Tambah Riwayat untuk menambahkan data.
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

@endsection
