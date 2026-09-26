<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $adherent = Auth::guard('adherent')->user();
        return view('adherent.dashboard', compact('adherent'));
    }
}