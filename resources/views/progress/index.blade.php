@extends('layouts.picobot')

@section('title', 'Monitoring Progres | PICOBOT')

@push('styles')
    <style>
        .muted {
            color: #8493a5;
            font-size: 11px;
        }

        .trend-good { color: #218653; font-weight: 600; }
        .trend-bad { color: #dc4545; font-weight: 600; }
        .trend-flat { color: #647b8e; font-weight: 600; }
    </style>
@endpush

@section('content')

    <section class="welcome">
        <h1>Monitoring Progres Rehabilitasi</h1>
        <p>
            Pantau perkembangan rehabilitasi setiap pasien berdasarkan riwayat latihan:
            frekuensi, durasi, dan tingkat nyeri. Ringkasan pada tabel dihitung dari
            data 30 hari terakhir.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <span>Monitoring Progres</span>
    </div>

    <h2 class="section-title">Ringkasan Umum</h2>

    <section class="stats">
        <div class="stat-card">
            <small>Total Pasien</small>
            <h2>{{ $stats['patients'] }}</h2>
        </div>

        <div class="stat-card">
            <small>Pasien Aktif (7 Hari Terakhir)</small>
            <h2>{{ $stats['active_7_days'] }}</h2>
        </div>

        <div class="stat-card">
            <small>Sesi Latihan (30 Hari Terakhir)</small>
            <h2>{{ $stats['sessions_30_days'] }}</h2>
        </div>
    </section>

    <section class="content-card">

        <div class="card-header">
            <div>
                <h3>Progres per Pasien</h3>
                <p>Klik "Lihat Progres" untuk grafik dan analisis lengkap.</p>
            </div>
        </div>

        <form action="{{ route('progress.index') }}" method="GET" class="toolbar">
            <input
                type="text"
                name="search"
                class="search-box"
                value="{{ $search }}"
                placeholder="Cari nama pasien atau nomor rekam medis..."
            >

            <button type="submit" class="btn btn-detail">Cari</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Pasien</th>
                        <th>Sesi (30 Hari)</th>
                        <th>Latihan Terakhir</th>
                        <th>Tren Nyeri</th>
                        <th>Tren Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($patients as $index => $patient)
                        @php
                            $summary = $summaries[$patient->id];
                            $pain = $summary['trend']['pain'];
                            $duration = $summary['trend']['duration'];
                            $last = $summary['totals']['last_session_at'];

                            $painClass = match ($pain['direction'] ?? null) {
                                'turun' => 'trend-good',
                                'naik' => 'trend-bad',
                                default => 'trend-flat',
                            };

                            $durationClass = match ($duration['direction'] ?? null) {
                                'naik' => 'trend-good',
                                'turun' => 'trend-bad',
                                default => 'trend-flat',
                            };
                        @endphp

                        <tr>
                            <td>{{ $patients->firstItem() + $index }}</td>

                            <td>
                                <strong>{{ $patient->name }}</strong>
                                <br>
                                <span class="muted">{{ $patient->medical_record_number }}</span>
                            </td>

                            <td>
                                {{ $summary['totals']['sessions'] }} sesi
                                <br>
                                <span class="muted">{{ $summary['totals']['sessions_per_week'] }} sesi/minggu</span>
                            </td>

                            <td>
                                @if($last)
                                    {{ $last->format('d/m/Y') }}
                                    <br>
                                    <span class="muted">
                                        {{ $summary['totals']['days_since_last'] === 0
                                            ? 'Hari ini'
                                            : $summary['totals']['days_since_last'] . ' hari lalu' }}
                                    </span>
                                @else
                                    <span class="muted">Belum ada latihan</span>
                                @endif
                            </td>

                            <td>
                                @if($pain)
                                    <span class="{{ $painClass }}">
                                        {{ $pain['earlier'] }} &rarr; {{ $pain['recent'] }}
                                    </span>
                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if($duration)
                                    <span class="{{ $durationClass }}">
                                        {{ $duration['change_percent'] > 0 ? '+' : '' }}{{ $duration['change_percent'] }}%
                                    </span>
                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>

                            <td>
                                @include('progress._status_badge', ['status' => $summary['status']])
                            </td>

                            <td>
                                <a href="{{ route('progress.show', $patient->id) }}" class="btn btn-detail">
                                    Lihat Progres
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                Data pasien tidak ditemukan.
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

@endsection
