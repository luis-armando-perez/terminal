<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanificarViajeController extends Controller
{
    public function index()
    {
        return view('planificar.planificar');
    }
}
