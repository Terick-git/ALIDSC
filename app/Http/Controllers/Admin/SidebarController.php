<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\View\Composers\AdminSidebarComposer;
use Illuminate\Http\JsonResponse;

class SidebarController extends Controller
{
    public function counts(): JsonResponse
    {
        return response()->json(AdminSidebarComposer::getCounts());
    }
}