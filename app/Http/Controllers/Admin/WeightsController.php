<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use App\Weight;

class WeightsController extends Controller
{
    /**
     * Refresh weights tables
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshData(Request $request)
    {
        try {
            (new Weight())->refresh();

            return Response::redirectTo("/admin")->withSuccess('Sort Code Weightings data successfully refreshed');

        } catch (RuntimeException $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf("An error was detected refreshing Sort Code Weightings data '%s'", $e->getMessage())
            );
        } catch (Exception $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf("A general error was detected refreshing Sort Code Weightings data '%s'", $e->getMessage())
            );
        }
    }
}
