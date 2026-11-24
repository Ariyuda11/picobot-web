@extends('layouts.picobot')

@section('title', ($isOwnProgress ? 'Progres Saya' : 'Progres Pasien') . ' | PICOBOT')

@push('styles')
    <style>
        .muted {
            color: #8493a5;
            font-size: 11px;
        }

        .period-tabs {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .period-tabs a {
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            background: #edf1f5;
            color: #647b8e;
        }

        .period-tabs a.active {
            background: #087e8b;
            color: white;
        }

        .stats.four {
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-card .sub {
            display: block;
            margin-top: 6px;
            color: #8493a5;
            font-size: 11px;
        }

        .status-panel {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            padding: 20px 22px;
            border-radius: 14px;
            margin-bottom: 25px;
            border: 1px solid #edf1f5;
            background: white;
        }

        .status-panel.membaik { border-left: 5px solid #218653; }
        .status-panel.stabil { border-left: 5px solid #078b88; }
        .status-panel.perlu_perhatian { border-left: 5px solid #dc4545; }
        .status-panel.belum_cukup_data { border-left: 5px solid #a4b1be; }

        .status-panel h3 {
            font-size: 15px;
            margin-bottom: 8px;
        }

        .status-panel ul {
            margin-left: 18px;
            font-size: 12px;
            line-height: 1.8;
            color: #425a70;
        }

        .status-panel .disclaimer {
            margin-top: 10px;
            font-size: 11px;
            color: #8493a5;
            line-height: 1.6;
        }

        .trend-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 25px;
        }

        .trend-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            border: 1px solid #edf1f5;
            box-shadow: 0 5px 20px rgba(0,0,0,.035);
        }

        .trend-card small {
            color: #8493a5;
            font-size: 12px;
        }

        .trend-card .values {
            display: flex;
            align-items: baseline;
            gap: 10px;
            margin-top: 10px;
            font-size: 22px;
            font-weight: 700;
            color: #123c5a;
        }

        .trend-card .values span.arrow {
            font-size: 14px;
            color: #8493a5;
        }

        .trend-good { color: #218653; }
        .trend-bad { color: #dc4545; }
        .trend-flat { color: #647b8e; }

        .chart-grid {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 18px;
            margin-bottom: 25px;
        }

        .chart-box {
            position: relative;
            height: 280px;
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-top: 25px;
        }

        .content-card table {
            min-width: 0;
        }

        @media(max-width: 900px) {
            .stats.four,
            .trend-grid,
            .chart-grid,
            .two-col {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    @php
        $indexRoute = $isOwnProgress ? 'progress.mine' : 'progress.show';
        $routeParams = $isOwnProgress ? [] : [$patient->id];
        $periodOptions = [7 => '7 Hari', 30 => '30 Hari', 90 => '90 Hari', 'all' => 'Semua'];
        $activePeriod = $period ?? 'all';
    @endphp

    <section class="welcome">
        <h1>{{ $isOwnProgress ? 'Progres Rehabilitasi Saya' : 'Progres Rehabilitasi Pasien' }}</h1>
        <p>
            @if($isOwnProgress)
                Lihat perkembangan latihan rehabilitasi Anda: seberapa rutin Anda berlatih,
                durasi latihan, dan perubahan tingkat nyeri dari waktu ke waktu.
            @else
                Analisis perkembangan rehabilitasi pasien berdasarkan riwayat latihan
                yang tercatat pada sistem PICOBOT.
            @endif
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        @if($isOwnProgress)
            <span>Progres Saya</span>
        @else
            <a href="{{ route('progress.index') }}">Monitoring Progres</a>
            <span>/</span>
            <span>{{ $patient->name }}</span>
        @endif
    </div>

    @if(! $patient)
        <section class="content-card">
            <div class="empty">
                Akun Anda belum terhubung dengan data pasien.
                <br>
                Silakan hubungi admin untuk menghubungkan akun dengan data pasien.
            </div>
        </section>
    @else
        @php
            $totals = $summary['totals'];
            $pain = $summary['trend']['pain'];
            $duration = $summary['trend']['duration'];
            $status = $summary['status'];
        @endphp

        <section class="content-card" style="margin-bottom: 25px;">
            <div class="card-header" style="margin-bottom: 0;">
                <div>
                    <h3>{{ $patient->name }}</h3>
                    <p>
                        {{ $patient->medical_record_number }}
                        &middot;
                        Periode {{ $summary['period']['start']->format('d/m/Y') }}
                        &ndash; {{ $summary['period']['end']->format('d/m/Y') }}
                    </p>
                </div>

                <nav class="period-tabs" aria-label="Pilih periode">
                    @foreach($periodOptions as $value => $label)
                        <a href="{{ route($indexRoute, array_merge($routeParams, ['period' => $value])) }}"
                           class="{{ (string) $activePeriod === (string) $value ? 'active' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </section>

        @if($totals['sessions'] === 0)
            <section class="content-card">
                <div class="empty">
                    Belum ada latihan yang tercatat pada periode ini.
                    @if($totals['last_session_at'])
                        <br>
                        Latihan terakhir: {{ $totals['last_session_at']->format('d/m/Y') }}
                        ({{ $totals['days_since_last'] }} hari lalu).
                    @endif
                    @if($isOwnProgress)
                        <br><br>
                        <a href="{{ route('my-exercise-histories.create') }}" class="btn btn-primary">
                            + Catat Latihan
                        </a>
                    @endif
                </div>
            </section>
        @else

            {{-- STATUS PROGRES --}}
            <section class="status-panel {{ $status['key'] }}">
                <div>
                    <h3>
                        Status Progres:
                        @include('progress._status_badge', ['status' => $status])
                    </h3>
                    <ul>
                        @foreach($status['reasons'] as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                    <p class="disclaimer">
                        Status ini adalah indikator otomatis berbasis aturan sederhana
                        (perbandingan paruh awal dan paruh akhir sesi pada periode terpilih),
                        bukan penilaian klinis. Keputusan terapi tetap berdasarkan evaluasi tenaga medis.
                    </p>
                </div>
            </section>

            {{-- RINGKASAN --}}
            <section class="stats four">
                <div class="stat-card">
                    <small>Total Sesi</small>
                    <h2>{{ $totals['sessions'] }}</h2>
                    <span class="sub">{{ $totals['sessions_per_week'] }} sesi per minggu</span>
                </div>

                <div class="stat-card">
                    <small>Total Durasi</small>
                    <h2>{{ $totals['total_minutes'] }} <span style="font-size: 14px;">menit</span></h2>
                    <span class="sub">Rata-rata {{ $totals['average_duration'] }} menit/sesi</span>
                </div>

                <div class="stat-card">
                    <small>Hari Aktif Berlatih</small>
                    <h2>{{ $totals['active_days'] }} <span style="font-size: 14px;">/ {{ $summary['period']['length_days'] }} hari</span></h2>
                    <span class="sub">
                        Terakhir:
                        {{ $totals['days_since_last'] === 0 ? 'hari ini' : $totals['days_since_last'] . ' hari lalu' }}
                    </span>
                </div>

                <div class="stat-card">
                    <small>Rata-rata Tingkat Nyeri</small>
                    <h2>{{ $totals['average_pain'] !== null ? $totals['average_pain'] : '-' }} <span style="font-size: 14px;">/ 10</span></h2>
                    <span class="sub">Skala 0 (tidak nyeri) – 10 (sangat nyeri)</span>
                </div>
            </section>

            {{-- TREN --}}
            <section class="trend-grid">
                <div class="trend-card">
                    <small>Tren Tingkat Nyeri (rata-rata paruh awal &rarr; paruh akhir)</small>
                    @if($pain)
                        @php
                            $painClass = match ($pain['direction']) {
                                'turun' => 'trend-good',
                                'naik' => 'trend-bad',
                                default => 'trend-flat',
                            };
                        @endphp
                        <div class="values">
                            {{ $pain['earlier'] }}
                            <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span>
                            <span class="{{ $painClass }}">{{ $pain['recent'] }}</span>
                        </div>
                        <span class="muted">
                            Selisih {{ $pain['delta'] > 0 ? '+' : '' }}{{ $pain['delta'] }} poin
                            ({{ ucfirst($pain['direction']) }})
                        </span>
                    @else
                        <div class="values trend-flat" style="font-size: 14px;">Belum cukup data</div>
                        <span class="muted">Butuh minimal 4 sesi dengan skor nyeri pada periode ini.</span>
                    @endif
                </div>

                <div class="trend-card">
                    <small>Tren Durasi Latihan (rata-rata paruh awal &rarr; paruh akhir)</small>
                    @if($duration)
                        @php
                            $durationClass = match ($duration['direction']) {
                                'naik' => 'trend-good',
                                'turun' => 'trend-bad',
                                default => 'trend-flat',
                            };
                        @endphp
                        <div class="values">
                            {{ $duration['earlier'] }}
                            <span class="arrow"><i class="fa-solid fa-arrow-right"></i></span>
                            <span class="{{ $durationClass }}">{{ $duration['recent'] }}</span>
                            <span style="font-size: 13px; font-weight: 500;">menit</span>
                        </div>
                        <span class="muted">
                            Perubahan {{ $duration['change_percent'] > 0 ? '+' : '' }}{{ $duration['change_percent'] }}%
                            ({{ ucfirst($duration['direction']) }})
                        </span>
                    @else
                        <div class="values trend-flat" style="font-size: 14px;">Belum cukup data</div>
                        <span class="muted">Butuh minimal 4 sesi pada periode ini.</span>
                    @endif
                </div>
            </section>

            {{-- GRAFIK --}}
            <section class="chart-grid">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h3>Durasi &amp; Nyeri per Sesi</h3>
                            <p>Batang: durasi (menit). Garis: tingkat nyeri (0–10).</p>
                        </div>
                    </div>
                    <div class="chart-box">
                        <canvas id="sessionChart" aria-label="Grafik durasi dan nyeri per sesi" role="img"></canvas>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h3>Distribusi Intensitas</h3>
                            <p>Jumlah sesi berdasarkan intensitas latihan.</p>
                        </div>
                    </div>
                    <div class="chart-box">
                        <canvas id="intensityChart" aria-label="Grafik distribusi intensitas" role="img"></canvas>
                    </div>
                </div>
            </section>

            {{-- TABEL --}}
            <section class="two-col">
                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h3>Rekap Mingguan</h3>
                            <p>Minggu dihitung Senin–Minggu.</p>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Minggu</th>
                                    <th>Sesi</th>
                                    <th>Durasi</th>
                                    <th>Nyeri</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['weekly'] as $week)
                                    <tr>
                                        <td>
                                            {{ $week['week_start']->format('d/m') }}
                                            &ndash; {{ $week['week_end']->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $week['sessions'] }}</td>
                                        <td>{{ $week['minutes'] }} menit</td>
                                        <td>{{ $week['average_pain'] !== null ? $week['average_pain'] . ' / 10' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header">
                        <div>
                            <h3>Per Jenis Latihan</h3>
                            <p>Diurutkan dari yang paling sering dilakukan.</p>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Jenis Latihan</th>
                                    <th>Sesi</th>
                                    <th>Rata-rata Durasi</th>
                                    <th>Nyeri</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($summary['by_exercise'] as $exercise)
                                    <tr>
                                        <td>
                                            {{ $exercise['name'] }}
                                            <br>
                                            <span class="muted">Terakhir {{ $exercise['last_at']->format('d/m/Y') }}</span>
                                        </td>
                                        <td>{{ $exercise['sessions'] }}</td>
                                        <td>{{ $exercise['average_duration'] }} menit</td>
                                        <td>{{ $exercise['average_pain'] !== null ? $exercise['average_pain'] . ' / 10' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        @endif

        <div class="form-actions">
            @if($isOwnProgress)
                <a href="{{ route('dashboard') }}" class="btn btn-cancel">Kembali</a>
                <a href="{{ route('my-exercise-histories.index') }}" class="btn btn-submit">Riwayat Latihan Saya</a>
            @else
                <a href="{{ route('progress.index') }}" class="btn btn-cancel">Kembali</a>
                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-detail" style="padding: 12px 19px; font-size: 12px;">Data Pasien</a>
                <a href="{{ route('exercise-histories.index', ['search' => $patient->medical_record_number]) }}" class="btn btn-submit">Riwayat Latihan</a>
            @endif
        </div>
    @endif

@endsection

@if($patient && $summary && $summary['totals']['sessions'] > 0)
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            (function () {
                const chartData = @json($summary['chart']);
                const intensity = @json($summary['intensity']);

                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.font.size = 11;
                Chart.defaults.color = '#647b8e';

                new Chart(document.getElementById('sessionChart'), {
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Durasi (menit)',
                                data: chartData.duration,
                                backgroundColor: 'rgba(8, 126, 139, 0.25)',
                                borderColor: '#087e8b',
                                borderWidth: 1,
                                borderRadius: 4,
                                maxBarThickness: 48,
                                yAxisID: 'yDuration',
                                order: 2,
                            },
                            {
                                type: 'line',
                                label: 'Tingkat Nyeri',
                                data: chartData.pain,
                                borderColor: '#dc4545',
                                backgroundColor: '#dc4545',
                                tension: 0.3,
                                spanGaps: true,
                                pointRadius: 3,
                                yAxisID: 'yPain',
                                order: 1,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            yDuration: {
                                position: 'left',
                                beginAtZero: true,
                                title: { display: true, text: 'Menit' },
                                grid: { color: '#edf1f5' },
                            },
                            yPain: {
                                position: 'right',
                                min: 0,
                                max: 10,
                                title: { display: true, text: 'Nyeri (0–10)' },
                                grid: { drawOnChartArea: false },
                            },
                            x: { grid: { display: false } },
                        },
                    },
                });

                new Chart(document.getElementById('intensityChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(intensity),
                        datasets: [{
                            data: Object.values(intensity),
                            backgroundColor: ['#34b37a', '#e3a63b', '#dc4545'],
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: { legend: { position: 'bottom' } },
                    },
                });
            })();
        </script>
    @endpush
@endif
