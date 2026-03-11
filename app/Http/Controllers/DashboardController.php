<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\FormResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_forms' => $user->forms()->count(),
            'total_responses' => FormResponse::whereIn('form_id', $user->forms()->pluck('id'))->count(),
            'active_forms' => $user->forms()->where('is_active', true)->count(),
            'forms_left' => $user->forms_left,
        ];

        $recentForms = $user->forms()
            ->withCount('responses')
            ->latest()
            ->take(5)
            ->get();

        $recentResponses = FormResponse::whereIn('form_id', $user->forms()->pluck('id'))
            ->with('form')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'recentForms', 'recentResponses'));
    }
}
