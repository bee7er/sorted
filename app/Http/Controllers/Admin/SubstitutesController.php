<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use App\Substitute;
use PHPUnit\Framework\InvalidDataProviderException;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class SubstitutesController extends Controller
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
            (new Substitute())->refresh();

            return Response::redirectTo("/admin")->withSuccess('Sort Code Substitutes data successfully refreshed');
        } catch (RuntimeException $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf("An error was detected refreshing Sort Code Substitutes data '%s'", $e->getMessage())
            );
        } catch (Exception $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf("A general error was detected refreshing Sort Code Substitutes data '%s'", $e->getMessage())
            );
        }
    }
}
