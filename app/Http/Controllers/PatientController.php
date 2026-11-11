<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    // Memastikan hanya Admin yang dapat mengelola data pasien
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403,
            'Anda tidak memiliki akses untuk mengelola data pasien.'
        );
    }

    // Menampilkan seluruh data pasien
    public function index()
    {
        $this->authorizeAdmin();

        $patients = Patient::latest()->paginate(10);

        return view('patients.index', compact('patients'));
    }

    // Menampilkan halaman tambah pasien
    public function create()
    {
        $this->authorizeAdmin();

        return view('patients.create');
    }

    // Menyimpan data pasien baru
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'medical_record_number' => [
                'required',
                'string',
                'max:50',
                'unique:patients,medical_record_number'
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'gender' => [
                'required',
                Rule::in(['Laki-laki', 'Perempuan'])
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'address' => [
                'nullable',
                'string'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],
        ]);

        Patient::create($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    // Menampilkan detail pasien
    public function show(Patient $patient)
    {
        $this->authorizeAdmin();

        return view('patients.show', compact('patient'));
    }

    // Menampilkan halaman edit pasien
    public function edit(Patient $patient)
    {
        $this->authorizeAdmin();

        return view('patients.edit', compact('patient'));
    }

    // Memperbarui data pasien
    public function update(Request $request, Patient $patient)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'medical_record_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('patients', 'medical_record_number')
                    ->ignore($patient->id)
            ],
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'gender' => [
                'required',
                Rule::in(['Laki-laki', 'Perempuan'])
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
            'address' => [
                'nullable',
                'string'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20'
            ],
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    // Menghapus data pasien
    public function destroy(Patient $patient)
    {
        $this->authorizeAdmin();

        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}
