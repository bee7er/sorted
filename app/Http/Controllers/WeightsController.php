<?php

namespace App\Http\Controllers;

use App\Weight;
use Illuminate\Http\Request;

class WeightsController extends Controller
{
    /**
     * Refresh weights tables
     * @return |bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refreshData(Request $request)
    {
        (new Weight())->refresh();

        return view('welcome');
    }
}
