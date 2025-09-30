<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Daftar pengajuan cuti
     */
    public function index()
    {
        if (auth()->user()->role === 'karyawan') {
            $requests = LeaveRequest::where('user_id', auth()->id())->get();
            return view('leave_requests.index', compact('requests'));
        } else {
            $waitingRequests = LeaveRequest::with('user')
                ->where('status', 'menunggu')
                ->get();

            $processedRequests = LeaveRequest::with('user')
                ->whereIn('status', ['approve', 'tolak'])
                ->get();

            return view('leave_requests.index', compact('waitingRequests', 'processedRequests'));
        }
    }

    /**
     * Simpan pengajuan cuti baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        LeaveRequest::create([
            'user_id'    => auth()->id(),
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'menunggu',
        ]);

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
    }

    /**
     * Update status pengajuan (approve/tolak) — hanya HRD/Admin
     */
    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        // Pastikan hanya HRD/Admin
        if (!in_array(auth()->user()->role, ['hrd','admin'])) {
            return redirect()->route('leave.index')->with('error', 'Anda tidak berhak mengubah status.');
        }

        $request->validate([
            'status' => 'required|in:approve,tolak',
        ]);

        $leave->update([
            'status' => $request->status,
        ]);

        return redirect()->route('leave.index')->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Form tambah pengajuan cuti
     */
    public function create()
    {
        return view('leave_requests.create');
    }

    /**
     * Form edit pengajuan cuti
     */
    public function edit(LeaveRequest $leave)
    {
        return view('leave_requests.edit', compact('leave'));
    }

    /**
     * Update pengajuan cuti (beserta status jika HRD/Admin)
     */
    public function update(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        $data = $request->only(['start_date', 'end_date', 'reason']);

        // Hanya HRD/Admin bisa update status
        if (in_array(auth()->user()->role, ['hrd','admin']) && $request->filled('status')) {
            if (in_array($request->status, ['menunggu','approve','tolak'])) {
                $data['status'] = $request->status;
            }
        }

        $leave->update($data);

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    /**
     * Hapus pengajuan cuti
     */
    public function destroy(LeaveRequest $leave)
    {
        $user = Auth::user();

        // Admin/HRD bisa hapus semua
        // Karyawan hanya bisa hapus miliknya sendiri yang masih menunggu
        if (
            in_array($user->role, ['admin', 'hrd']) ||
            ($user->id === $leave->user_id && $leave->status === 'menunggu')
        ) {
            $leave->delete();
            return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
        }

        return redirect()->route('leave.index')->with('error', 'Anda tidak berhak menghapus pengajuan ini.');
    }
}
