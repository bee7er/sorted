<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Substitute;
use App\Weight;
use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use RuntimeException;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin', ['weightsTableUrl' => env('SORT_CODE_IMPORT_WEIGHTS_URL'), 'substitutesTableUrl' => env
        ('SORT_CODE_IMPORT_SUBSTITUTES_URL')]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function action()
    {
        try {
            $params = request()->all();

            switch (true) {
                case isset($params['downloadWeights']):
                    if ($this->downloadWeights()) {
                        return Response::redirectTo("/admin")->withSuccess('Sort Code Weightings data successfully
                        down loaded');
                    }

                    session()->flash('fail', 'Unknown error downloading Sort Code Weightings data');

                    break;
                case isset($params['downloadSubstitutes']):
                    if ($this->downloadSubstitutes()) {
                        return Response::redirectTo("/admin")->withSuccess('Sort Code Substututes data successfully
                        down loaded');
                    }

                    session()->flash('fail', 'Unknown error downloading Sort Code Substututes data');

                    break;
                case isset($params['refreshWeights']):
                    if ($this->refreshWeights()) {
                        return Response::redirectTo("/admin")->withSuccess('Sort Code Weightings data successfully
                        refreshed');
                    }

                    session()->flash('fail', 'Unknown error refreshing Sort Code Weightings data');

                    break;
                case isset($params['refreshSubstitutes']):
                    if ($this->refreshSubstitutes()) {
                        return Response::redirectTo("/admin")->withSuccess('Sort Code Substitutes data successfully
                        refreshed');
                    }

                    session()->flash('fail', 'Unknown error refreshing Sort Code Substitute data');

                    break;
            }
        } catch (Exception $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf('Unexpected error: ' . $e->getMessage())
            );
        }

        return view('admin', ['weightsTableUrl' => env('SORT_CODE_IMPORT_WEIGHTS_URL'), 'substitutesTableUrl' => env
        ('SORT_CODE_IMPORT_SUBSTITUTES_URL')]);
    }

    /**
     * Downloads the Sort code weights data file
     *
     * @throws RuntimeException
     */
    public function downloadWeights()
    {
        throw new RuntimeException('downloadWeights');
    }

    /**
     * Downloads the Sort code substitutes data file
     *
     * @throws RuntimeException
     */
    public function downloadSubstitutes()
    {
        throw new RuntimeException('downloadSubstitutes');
    }

    /**
     * Refreshes the Sort code weights data from the filesystem file
     *
     * @throws RuntimeException
     */
    public function refreshWeights()
    {
        //TODO check if the file data is already up to date

        (new Weight())->refresh();

        return true;
    }

    /**
     * Refreshes the Sort code substitutes data from the filesystem file
     *
     * @throws RuntimeException
     */
    public function refreshSubstitutes()
    {
        //TODO check if the file data is already up to date

        (new Substitute())->refresh();

        return true;
    }
}
