<?php

namespace App\Http\Controllers;

use App\Models\ExerciseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MyExerciseHistoryController extends Controller
{
    // Memastikan hanya akun pasien yang dapat mengakses riwayat latihannya
    private function authorizePatient(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'pasien',
            403,
            'Menu ini hanya dapat diakses oleh pasien.'
        );
    }

    // Menampilkan riwayat latihan milik pasien yang sedang login
    public function index(Request $request)
    {
        $this->authorizePatient();

        $patient = Auth::user()->patient;
        $search = trim((string) $request->input('search'));

        $histories = null;
        $stats = [
            'total' => 0,
            'today' => 0,
            'average_duration' => 0,
        ];

        if ($patient) {
            $baseQuery = fn () => ExerciseHistory::where('patient_id', $patient->id);

            $histories = $baseQuery()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where('exercise_name', 'like', "%{$search}%");
                })
                ->latest('performed_at')
                ->paginate(10)
                ->withQueryString();

            $stats = [
                'total' => $baseQuery()->count(),
                'today' => $baseQuery()->whereDate('performed_at', today())->count(),
                'average_duration' => (int) round($baseQuery()->avg('duration_minutes') ?? 0),
            ];
        }

        return view('my_exercise_histories.index', compact('patient', 'histories', 'stats', 'search'));
    }

    // Menampilkan halaman pencatatan latihan baru
    public function create()
    {
        $this->authorizePatient();

        $patient = Auth::user()->patient;

        if (! $patient) {
            return redirect()
                ->route('my-exercise-histories.index')
                ->with('error', 'Akun Anda belum terhubung dengan data pasien. Silakan hubungi admin.');
        }

        return view('my_exercise_histories.create', compact('patient'));
    }

    // Menyimpan riwayat latihan milik pasien
    public function store(Request $request)
    {
        $this->authorizePatient();

        $patient = Auth::user()->patient;

        if (! $patient) {
            return redirect()
                ->route('my-exercise-histories.index')
                ->with('error', 'Akun Anda belum terhubung dengan data pasien. Silakan hubungi admin.');
        }

        $validated = $this->validateHistory($request);
        $validated['patient_id'] = $patient->id;
        $validated['recorded_by'] = Auth::id();

        ExerciseHistory::create($validated);

        return redirect()
            ->route('my-exercise-histories.index')
            ->with('success', 'Riwayat latihan berhasil dicatat.');
    }

    // Menampilkan detail riwayat latihan milik pasien
    public function show(ExerciseHistory $exerciseHistory)
    {
        $this->authorizePatient();

        $patient = Auth::user()->patient;

        abort_unless(
            $patient && $exerciseHistory->patient_id === $patient->id,
            403,
            'Anda tidak memiliki akses ke riwayat latihan ini.'
        );

        $exerciseHistory->load(['patient', 'recorder']);

        return view('my_exercise_histories.show', compact('exerciseHistory'));
    }

    // Aturan validasi data riwayat latihan
    private function validateHistory(Request $request): array
    {
        return $request->validate([
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
