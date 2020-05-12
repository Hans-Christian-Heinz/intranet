<?php

namespace App\Http\Controllers;

use App\Exemption;
use App\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $exemptionCount = Exemption::where('status', 'new')->count();
        $userCount = User::count();

        return view('admin.index', compact('exemptionCount', 'userCount'));
    }
}
