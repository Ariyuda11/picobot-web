@extends('layouts.picobot')

@section('title', 'Catat Latihan | PICOBOT')

@section('content')

    <section class="welcome">
        <h1>Catat Latihan Rehabilitasi</h1>
        <p>
            Isi formulir berikut setiap selesai melakukan latihan rehabilitasi.
            Catatan yang Anda simpan akan membantu pemantauan progres pemulihan.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('my-exercise-histories.index') }}">Riwayat Latihan Saya</a>
        <span>/</span>
        <span>Catat Latihan</span>
    </div>

    <section class="form-card">

        <div class="form-header">
            <h2>Formulir Latihan Rehabilitasi</h2>
            <p>
                Pasien: <strong>{{ $patient->name }}</strong>
                ({{ $patient->medical_record_number }})<br>
                Kolom bertanda <span class="required">*</span> wajib diisi.
            </p>
        </div>

        <form action="{{ route('my-exercise-histories.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="performed_at">
                        Tanggal &amp; Waktu Latihan <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        id="performed_at"
                        name="performed_at"
                        class="form-control @error('performed_at') is-invalid @enderror"
                        value="{{ old('performed_at', now()->format('Y-m-d\TH:i')) }}"
                        max="{{ now()->format('Y-m-d\TH:i') }}"
                        required
                    >

                    @error('performed_at')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="intensity">
                        Intensitas Latihan <span class="required">*</span>
                    </label>

                    <select
                        id="intensity"
                        name="intensity"
                        class="form-control @error('intensity') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Intensitas --</option>

                        @foreach(['Ringan', 'Sedang', 'Berat'] as $intensity)
                            <option
                                value="{{ $intensity }}"
                                {{ old('intensity', 'Sedang') === $intensity ? 'selected' : '' }}
                            >
                                {{ $intensity }}
                            </option>
                        @endforeach
                    </select>

                    @error('intensity')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="exercise_name">
                        Jenis Latihan <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="exercise_name"
                        name="exercise_name"
                        class="form-control @error('exercise_name') is-invalid @enderror"
                        value="{{ old('exercise_name') }}"
                        list="exerciseSuggestions"
                        placeholder="Contoh: Latihan Rentang Gerak Sendi"
                        maxlength="255"
                        required
                    >

                    <datalist id="exerciseSuggestions">
                        <option value="Latihan Rentang Gerak Sendi"></option>
                        <option value="Latihan Kekuatan Otot"></option>
                        <option value="Latihan Keseimbangan"></option>
                        <option value="Latihan Berjalan"></option>
                        <option value="Latihan Koordinasi Tangan"></option>
                        <option value="Latihan Motorik Halus"></option>
                    </datalist>

                    @error('exercise_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="duration_minutes">
                        Durasi Latihan (menit) <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="duration_minutes"
                        name="duration_minutes"
                        class="form-control @error('duration_minutes') is-invalid @enderror"
                        value="{{ old('duration_minutes') }}"
                        min="1"
                        max="600"
                        placeholder="Contoh: 30"
                        required
                    >

                    @error('duration_minutes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="repetitions">Jumlah Pengulangan</label>

                    <input
                        type="number"
                        id="repetitions"
                        name="repetitions"
                        class="form-control @error('repetitions') is-invalid @enderror"
                        value="{{ old('repetitions') }}"
                        min="1"
                        max="1000"
                        placeholder="Contoh: 10"
                    >

                    @error('repetitions')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pain_level">Tingkat Nyeri (0 - 10)</label>

                    <input
                        type="number"
                        id="pain_level"
                        name="pain_level"
                        class="form-control @error('pain_level') is-invalid @enderror"
                        value="{{ old('pain_level') }}"
                        min="0"
                        max="10"
                        placeholder="0 = tidak nyeri, 10 = sangat nyeri"
                    >

                    @error('pain_level')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="notes">Catatan Latihan</label>

                    <textarea
                        id="notes"
                        name="notes"
                        class="form-control @error('notes') is-invalid @enderror"
                        placeholder="Tuliskan kondisi tubuh, kendala, atau keluhan selama latihan..."
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="form-note">
                Pastikan data latihan yang Anda isi sudah benar.
                Riwayat ini akan terhubung dengan akun dan data pasien Anda.
            </div>

            <div class="form-actions">
                <a href="{{ route('my-exercise-histories.index') }}" class="btn btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn btn-submit">
                    Simpan Riwayat Latihan
                </button>
            </div>

        </form>

    </section>

@endsection
