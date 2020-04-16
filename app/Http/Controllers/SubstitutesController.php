<?php

namespace App\Http\Controllers;

use App\Substitute;
use Illuminate\Http\Request;

class SubstitutesController extends Controller
{
    /**
     * Refresh weights tables
     * @return |bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function refreshData(Request $request)
    {
        (new Substitute())->refresh();

        return view('welcome');
    }
}
