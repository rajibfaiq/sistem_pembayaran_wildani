<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.payment-report');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginId = $request->input('login_id');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Check if login_id is email or NISN
        $field = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';

        if (Auth::attempt([$field => $loginId, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.payment-report'));
            } else {
                return redirect()->intended(route('student.dashboard'));
            }
        }

        return back()->withErrors([
            'login_id' => 'Email/NISN atau password yang Anda masukkan salah.',
        ])->onlyInput('login_id');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Display the admin payment report and management page.
     */
    public function paymentReport(Request $request): View
    {
        $paymentTypes = PaymentType::all();
        $allStudents = Student::orderBy('nama')->get();

        $filterStatus = $request->get('status', 'all');
        $filterPaymentType = $request->get('payment_type_id', '');
        $filterKelas = $request->get('kelas', '');
        $search = $request->get('search', '');

        // Students with their bills eager loaded
        $studentsQuery = Student::with(['bills' => function ($query) use ($filterPaymentType): void {
            $query->with('paymentType');
            if ($filterPaymentType) {
                $query->where('payment_type_id', $filterPaymentType);
            }
        }]);

        if ($search) {
            $studentsQuery->where(function ($q) use ($search): void {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($filterKelas) {
            $studentsQuery->where('kelas', $filterKelas);
        }

        $students = $studentsQuery->get();

        // Separate students who have paid and who haven't
        $paidStudents = collect();
        $unpaidStudents = collect();

        foreach ($students as $student) {
            $bills = $student->bills;

            $hasPaid = $bills->where('status', 'paid')->isNotEmpty();
            $hasPending = $bills->where('status', '!=', 'paid')->isNotEmpty();

            if ($hasPaid) {
                $paidStudents->push($student);
            }
            if ($hasPending || $bills->isEmpty()) {
                $unpaidStudents->push($student);
            }
        }

        // Summary stats
        $totalPaid = Bill::where('status', 'paid')->sum('amount');
        $totalPending = Bill::where('status', 'pending')->sum('amount');
        $totalBills = Bill::count();
        $paidBills = Bill::where('status', 'paid')->count();

        // Unique kelas for filter
        $kelasList = Student::distinct()->pluck('kelas')->sort()->values();

        return view('admin.payment-report', compact(
            'paymentTypes',
            'paidStudents',
            'unpaidStudents',
            'allStudents',
            'filterStatus',
            'filterPaymentType',
            'filterKelas',
            'search',
            'totalPaid',
            'totalPending',
            'totalBills',
            'paidBills',
            'kelasList',
        ));
    }

    /**
     * Mark a bill as paid (admin action).
     */
    public function markBillAsPaid(Bill $bill): RedirectResponse
    {
        $bill->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', "Tagihan {$bill->paymentType->name} untuk siswa {$bill->student->nama} berhasil ditandai lunas!");
    }

    /**
     * Store a new payment type (Layanan Pembayaran) to display on public dashboard.
     */
    public function storePaymentType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:payment_types,name'],
            'description' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'icon' => ['required', 'string', 'in:calendar,building,shirt,sparkles,book,graduation-cap'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        PaymentType::create($validated);

        return back()->with('success', 'Layanan pembayaran baru berhasil ditambahkan dan ditayangkan di dashboard publik!');
    }

    /**
     * Store a new student (Siswa) and auto-create login user account.
     */
    public function storeStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'string', 'max:20', 'unique:students,nisn'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', 'max:50'],
            'parent_phone' => ['required', 'string', 'max:20'],
        ]);

        $student = Student::create($validated);

        // Auto-create unified login account for the student
        User::create([
            'name' => $student->nama,
            'email' => strtolower(explode(' ', trim($student->nama))[0]).'_'.time().'@wildani.sch.id',
            'password' => bcrypt('siswa123'),
            'role' => 'student',
            'nisn' => $student->nisn,
            'student_id' => $student->id,
        ]);

        return back()->with('success', 'Siswa baru berhasil ditambahkan! Akun login otomatis dibuat dengan NISN sebagai ID dan password default: siswa123.');
    }

    /**
     * Store a new bill (Tagihan) for a student.
     */
    public function storeBill(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'payment_type_id' => ['required', 'exists:payment_types,id'],
            'due_date' => ['required', 'date'],
        ]);

        $paymentType = PaymentType::findOrFail($validated['payment_type_id']);

        Bill::create([
            'student_id' => $validated['student_id'],
            'payment_type_id' => $validated['payment_type_id'],
            'amount' => $paymentType->amount,
            'status' => 'pending',
            'due_date' => $validated['due_date'],
        ]);

        return back()->with('success', 'Tagihan baru berhasil diterbitkan untuk siswa!');
    }

    /**
     * Display the student dashboard.
     */
    public function studentDashboard(): View
    {
        $user = Auth::user();

        $student = Student::with(['bills' => function ($query): void {
            $query->with('paymentType')->latest();
        }])->findOrFail($user->student_id);

        $pendingBills = $student->bills->where('status', '!=', 'paid');
        $paidBills = $student->bills->where('status', 'paid');

        $totalTagihan = $pendingBills->sum('amount');
        $totalTerbayar = $paidBills->sum('amount');

        $paymentTypes = PaymentType::all();

        return view('student.dashboard', compact(
            'student',
            'pendingBills',
            'paidBills',
            'totalTagihan',
            'totalTerbayar',
            'paymentTypes'
        ));
    }
}
