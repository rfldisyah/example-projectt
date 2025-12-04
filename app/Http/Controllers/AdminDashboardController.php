<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Diary;
use App\Models\DiaryAnalysis;
use App\Models\Testimony;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // HANYA MENGAMBIL DATA COUNT (AMAN UNTUK PRIVASI)
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_diaries' => Diary::count(),
            'total_analyses' => DiaryAnalysis::count(),
            'happy_mood_count' => DiaryAnalysis::where('mood', 'Happy')->count(), // Statistik global anonim
            'testimonials_count' => Testimony::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
