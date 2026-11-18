@extends('layouts.picobot')

@section('title', 'Detail Riwayat Latihan | PICOBOT')

@section('content')

    @php
        $badgeClass = match ($exerciseHistory->intensity) {
            'Ringan' => 'badge-ringan',
            'Berat' => 'badge-berat',
            default => 'badge-sedang',
        };
    @endphp

    <section class="welcome">
        <h1>Detail Riwayat Latihan</h1>
        <p>
            Informasi lengkap aktivitas latihan rehabilitasi yang tercatat
            pada sistem PICOBOT.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('exercise-histories.index') }}">Riwayat Latihan</a>
        <span>/</span>
        <span>Detail Riwayat</span>
    </div>

    <section class="detail-card">

        <div class="detail-header">
            <div>
                <h2>{{ $exerciseHistory->exercise_name }}</h2>
                <p>
                    {{ $exerciseHistory->performed_at->format('d F Y, H:i') }} WIB
                </p>
            </div>

            <span class="badge {{ $badgeClass }}">
                Intensitas {{ $exerciseHistory->intensity }}
            </span>
        </div>

        <div class="detail-grid">

            <div class="detail-item">
                <small>Nama Pasien</small>
                <h3>{{ $exerciseHistory->patient->name ?? '-' }}</h3>
            </div>

            <div class="detail-item">
                <small>Nomor Rekam Medis</small>
                <h3>{{ $exerciseHistory->patient->medical_record_number ?? '-' }}</h3>
            </div>

            <div class="detail-item">
                <small>Jenis Latihan</small>
                <h3>{{ $exerciseHistory->exercise_name }}</h3>
            </div>

            <div class="detail-item">
                <small>Intensitas</small>
                <h3>{{ $exerciseHistory->intensity }}</h3>
            </div>

            <div class="detail-item">
                <small>Durasi Latihan</small>
                <h3>{{ $exerciseHistory->duration_minutes }} menit</h3>
            </div>

            <div class="detail-item">
                <small>Jumlah Pengulangan</small>
                <h3>
                    {{ $exerciseHistory->repetitions !== null
                        ? $exerciseHistory->repetitions . ' kali'
                        : '-' }}
                </h3>
            </div>

            <div class="detail-item">
                <small>Tingkat Nyeri</small>
                <h3>
                    {{ $exerciseHistory->pain_level !== null
                        ? $exerciseHistory->pain_level . ' / 10'
                        : '-' }}
                </h3>
            </div>

            <div class="detail-item">
                <small>Dicatat Oleh</small>
                <h3>{{ $exerciseHistory->recorder->name ?? 'Sistem' }}</h3>
            </div>

            <div class="detail-item full">
                <small>Catatan Latihan</small>
                <h3>{{ $exerciseHistory->notes ?: '-' }}</h3>
            </div>

            <div class="detail-item">
                <small>Tanggal Data Dibuat</small>
                <h3>{{ $exerciseHistory->created_at->format('d F Y, H:i') }}</h3>
            </div>

            <div class="detail-item">
                <small>Terakhir Diperbarui</small>
                <h3>{{ $exerciseHistory->updated_at->format('d F Y, H:i') }}</h3>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('exercise-histories.index') }}" class="btn btn-cancel">
                Kembali
            </a>

            <a href="{{ route('exercise-histories.edit', $exerciseHistory->id) }}" class="btn btn-submit">
                Edit Riwayat
            </a>
        </div>

    </section>

@endsection
