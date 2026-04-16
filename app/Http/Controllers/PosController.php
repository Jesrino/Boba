<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display the placeholder POS dashboard.
     */
    public function index(Request $request): View
    {
        return view('pos.index', [
            'user' => $request->user(),
        ]);
    }
}
