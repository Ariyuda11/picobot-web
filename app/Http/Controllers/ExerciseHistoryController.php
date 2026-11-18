<?php

namespace App\Http\Controllers;

use App\Models\ExerciseHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExerciseHistoryController extends Controller
{
    // Memastikan hanya Admin yang dapat mengelola seluruh riwayat latihan
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403,
            'Anda tidak memiliki akses untuk mengelola riwayat latihan.'
        );
    }

    // Menampilkan seluruh riwayat latihan rehabilitasi
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $search = trim((string) $request->input('search'));

        $histories = ExerciseHistory::with(['patient', 'recorder'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('exercise_name', 'like', "%{$search}%")
                        ->orWhereHas('patient', function ($patientQuery) use ($search) {
                            $patientQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('medical_record_number', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('performed_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => ExerciseHistory::count(),
            'today' => ExerciseHistory::whereDate('performed_at', today())->count(),
            'average_duration' => (int) round(ExerciseHistory::avg('duration_minutes') ?? 0),
        ];

        return view('exercise_histories.index', compact('histories', 'stats', 'search'));
    }

    // Menampilkan halaman tambah riwayat latihan
    public function create()
    {
        $this->authorizeAdmin();

        $patients = Patient::orderBy('name')->get();

        return view('exercise_histories.create', compact('patients'));
    }

    // Menyimpan riwayat latihan baru
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateHistory($request);
        $validated['recorded_by'] = Auth::id();

        ExerciseHistory::create($validated);

        return redirect()
            ->route('exercise-histories.index')
            ->with('success', 'Riwayat latihan berhasil ditambahkan.');
    }

    // Menampilkan detail riwayat latihan
    public function show(ExerciseHistory $exerciseHistory)
    {
        $this->authorizeAdmin();

        $exerciseHistory->load(['patient', 'recorder']);

        return view('exercise_histories.show', compact('exerciseHistory'));
    }

    // Menampilkan halaman edit riwayat latihan
    public function edit(ExerciseHistory $exerciseHistory)
    {
        $this->authorizeAdmin();

        $patients = Patient::orderBy('name')->get();

        return view('exercise_histories.edit', compact('exerciseHistory', 'patients'));
    }

    // Memperbarui riwayat latihan
    public function update(Request $request, ExerciseHistory $exerciseHistory)
    {
        $this->authorizeAdmin();

        $validated = $this->validateHistory($request);

        $exerciseHistory->update($validated);

        return redirect()
            ->route('exercise-histories.index')
            ->with('success', 'Riwayat latihan berhasil diperbarui.');
    }

    // Menghapus riwayat latihan
    public function destroy(ExerciseHistory $exerciseHistory)
    {
        $this->authorizeAdmin();

        $exerciseHistory->delete();

        return redirect()
            ->route('exercise-histories.index')
            ->with('success', 'Riwayat latihan berhasil dihapus.');
    }

    // Aturan validasi data riwayat latihan
    private function validateHistory(Request $request): array
    {
        return $request->validate([
            'patient_id' => [
                'required',
                'integer',
                Rule::exists('patients', 'id'),
            ],
            'exercise_name' => [
                'required',
                'string',
                'max:255',
            ],
            'intensity' => [
                'required',
                Rule::in(['Ringan', 'Sedang', 'Berat']),
            ],
            'duration_minutes' => [
                'required',
                'integer',
                'min:1',
                'max:600',
            ],
            'repetitions' => [
                'nullable',
                'integer',
                'min:1',
                'max:1000',
            ],
            'pain_level' => [
                'nullable',
                'integer',
                'min:0',
                'max:10',
            ],
            'performed_at' => [
                'required',
                'date',
                'before_or_equal:now',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ]);
    }
}
