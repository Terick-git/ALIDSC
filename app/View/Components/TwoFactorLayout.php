<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class TwoFactorLayout extends Component
{
    public function render(): View
    {
        return view('layouts.2fa');
    }
}