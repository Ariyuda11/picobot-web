@extends('layouts.picobot')

@section('title', 'Edit Riwayat Latihan | PICOBOT')

@section('content')

    <section class="welcome">
        <h1>Edit Riwayat Latihan</h1>
        <p>
            Perbarui data aktivitas latihan rehabilitasi pasien sesuai
            catatan terbaru yang telah diobservasi.
        </p>
    </section>

    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span>/</span>
        <a href="{{ route('exercise-histories.index') }}">Riwayat Latihan</a>
        <span>/</span>
        <span>Edit Riwayat</span>
    </div>

    <section class="form-card">

        <div class="form-header">
            <h2>Formulir Edit Riwayat Latihan</h2>
            <p>
                Ubah data latihan pasien sesuai kondisi terbaru.
                Kolom bertanda <span class="required">*</span> wajib diisi.
            </p>
        </div>

        <form action="{{ route('exercise-histories.update', $exerciseHistory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="patient_id">
                        Pasien <span class="required">*</span>
                    </label>

                    <select
                        id="patient_id"
                        name="patient_id"
                        class="form-control @error('patient_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Pasien --</option>

                        @foreach($patients as $patient)
                            <option
                                value="{{ $patient->id }}"
                                {{ (string) old('patient_id', $exerciseHistory->patient_id) === (string) $patient->id ? 'selected' : '' }}
                            >
                                {{ $patient->name }} ({{ $patient->medical_record_number }})
                            </option>
                        @endforeach
                    </select>

                    @error('patient_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="performed_at">
                        Tanggal &amp; Waktu Latihan <span class="required">*</span>
                    </label>

                    <input
                        type="datetime-local"
                        id="performed_at"
                        name="performed_at"
                        class="form-control @error('performed_at') is-invalid @enderror"
                        value="{{ old('performed_at', $exerciseHistory->performed_at->format('Y-m-d\TH:i')) }}"
                        max="{{ now()->format('Y-m-d\TH:i') }}"
                        required
                    >

                    @error('performed_at')
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
                        value="{{ old('exercise_name', $exerciseHistory->exercise_name) }}"
                        list="exerciseSuggestions"
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
                    <label for="intensity">
                        Intensitas Latihan <span class="required">*</span>
                    </label>

                    <select
                        id="intensity"
                        name="intensity"
                        class="form-control @error('intensity') is-invalid @enderror"
                        required
                    >
                        @foreach(['Ringan', 'Sedang', 'Berat'] as $intensity)
                            <option
                                value="{{ $intensity }}"
                                {{ old('intensity', $exerciseHistory->intensity) === $intensity ? 'selected' : '' }}
                            >
                                {{ $intensity }}
                            </option>
                        @endforeach
                    </select>

                    @error('intensity')
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
                        value="{{ old('duration_minutes', $exerciseHistory->duration_minutes) }}"
                        min="1"
                        max="600"
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
                        value="{{ old('repetitions', $exerciseHistory->repetitions) }}"
                        min="1"
                        max="1000"
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
                        value="{{ old('pain_level', $exerciseHistory->pain_level) }}"
                        min="0"
                        max="10"
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
                        placeholder="Tuliskan kondisi pasien, kendala, atau catatan tambahan selama latihan..."
                    >{{ old('notes', $exerciseHistory->notes) }}</textarea>

                    @error('notes')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="form-actions">
                <a href="{{ route('exercise-histories.index') }}" class="btn btn-cancel">
                    Batal
                </a>

                <button type="submit" class="btn btn-submit">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </section>

@endsection
