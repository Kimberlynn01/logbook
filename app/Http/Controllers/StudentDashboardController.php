<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $summary = [
            'approved' => 24,
            'pending' => 2,
            'rejected' => 1,
        ];

        $todayEntry = [
            'date' => 'Senin, 13 Juli 2026',
            'status' => 'pending',
            'preview' => 'Melakukan setup environment development, mengikuti briefing tim, dan mereview dokumentasi API internal.',
        ];

        $history = [
            [
                'date' => 'Senin, 13 Juli 2026',
                'summary' => 'Setup environment development, briefing tim...',
                'status' => 'pending',
            ],
            [
                'date' => 'Minggu, 12 Juli 2026',
                'summary' => 'Hari Libur — Cuti Bersama',
                'status' => 'holiday',
            ],
            [
                'date' => 'Jumat, 10 Juli 2026',
                'summary' => 'Revisi dokumentasi API sesuai catatan mentor.',
                'status' => 'rejected',
            ],
        ];

        $documents = [
            ['name' => 'laporan-revisi-api.pdf', 'date' => 'Jumat, 10 Juli 2026'],
            ['name' => 'setup-log.pdf', 'date' => 'Senin, 13 Juli 2026'],
        ];

        return view('student.index', compact('summary', 'todayEntry', 'history', 'documents'));
    }
}
