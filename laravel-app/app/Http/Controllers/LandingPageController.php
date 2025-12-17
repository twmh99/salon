<?php

namespace App\Http\Controllers;

use App\Support\TreatmentCatalogue;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        return view('landing', [
            'title' => 'Reservasi Treatment Kecantikan',
            'treatments' => TreatmentCatalogue::all(),
        ]);
    }
}
