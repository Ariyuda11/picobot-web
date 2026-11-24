<?php

namespace App\Http\Controllers;

use App\Models\ExerciseHistory;
use App\Models\Patient;
use App\Services\RehabProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressMonitoringController extends Controller
{
    public function __construct(private RehabProgressService $progress)
    {
    }

    // Memastikan hanya Admin yang dapat memantau progres seluruh pasien
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'admin',
            403,
            'Anda tidak memiliki akses untuk memantau progres seluruh pasien.'
        );
    }

    // Memastikan hanya akun pasien yang dapat mengakses progres miliknya
    private function authorizePatient(): void
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'pasien',
            403,
            'Menu ini hanya dapat diakses oleh pasien.'
        );
    }

    // Menampilkan daftar pasien beserta ringkasan progres 30 hari terakhir (Admin)
    public function index(Request $request)
    {
        // Pasien diarahkan ke halaman progres miliknya sendiri
        if (Auth::user()->role === 'pasien') {
            return redirect()->route('progress.mine');
        }

        $this->authorizeAdmin();

        $search = trim((string) $request->input('search'));

        $patients = Patient::query()
            ->with('exerciseHistories')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('medical_record_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Ringkasan progres per pasien pada halaman yang sedang ditampilkan
        $summaries = $patients->getCollection()
            ->mapWithKeys(fn (Patient $patient) => [
                $patient->id => $this->progress->forPatient($patient, 30),
            ]);

        $stats = [
            'patients' => Patient::count(),
            'active_7_days' => Patient::whereHas('exerciseHistories', function ($query) {
                $query->where('performed_at', '>=', now()->subDays(6)->startOfDay())
                    ->where('performed_at', '<=', now());
            })->count(),
            'sessions_30_days' => ExerciseHistory::where('performed_at', '>=', now()->subDays(29)->startOfDay())
                ->where('performed_at', '<=', now())
                ->count(),
        ];

        return view('progress.index', compact('patients', 'summaries', 'stats', 'search'));
    }

    // Menampilkan detail progres rehabilitasi satu pasien (Admin)
    public function show(Request $request, Patient $patient)
    {
        $this->authorizeAdmin();

        $period = RehabProgressService::parsePeriod($request->input('period'));
        $summary = $this->progress->forPatient($patient, $period);
        $isOwnProgress = false;

        return view('progress.show', compact('patient', 'summary', 'period', 'isOwnProgress'));
    }

    // Menampilkan progres rehabilitasi milik pasien yang sedang login
    public function mine(Request $request)
    {
        $this->authorizePatient();

        $patient = Auth::user()->patient;
        $period = RehabProgressService::parsePeriod($request->input('period'));
        $summary = $patient ? $this->progress->forPatient($patient, $period) : null;
        $isOwnProgress = true;

        return view('progress.show', compact('patient', 'summary', 'period', 'isOwnProgress'));
    }
}
