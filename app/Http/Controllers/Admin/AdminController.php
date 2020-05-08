<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Substitute;
use App\Weight;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
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
     * Show the application dashboard
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Clear all messages
        Session::forget(['success', 'fail']);

        return view('admin', [
            'weightsTableUrl' => env('SORT_CODE_IMPORT_WEIGHTS_URL'),
            'substitutesTableUrl' => env('SORT_CODE_IMPORT_SUBSTITUTES_URL')
        ]);
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
                        return Response::redirectTo("/admin")
                            ->withSuccess('Sort Code Weightings data successfully downloaded');
                    }

                    session()->flash('fail', 'Unknown error downloading Sort Code Weightings data');

                    break;
                case isset($params['downloadSubstitutes']):
                    if ($this->downloadSubstitutes()) {
                        return Response::redirectTo("/admin")
                            ->withSuccess('Sort Code Substututes data successfully downloaded');
                    }

                    session()->flash('fail', 'Unknown error downloading Sort Code Substututes data');

                    break;
                case isset($params['refreshWeights']):
                    if ($this->refreshWeights()) {
                        return Response::redirectTo("/admin")
                            ->withSuccess('Sort Code Weightings data successfully refreshed');
                    }

                    session()->flash('fail', 'Unknown error refreshing Sort Code Weightings data');

                    break;
                case isset($params['refreshSubstitutes']):
                    if ($this->refreshSubstitutes()) {
                        return Response::redirectTo("/admin")
                            ->withSuccess('Sort Code Substitutes data successfully refreshed');
                    }

                    session()->flash('fail', 'Unknown error refreshing Sort Code Substitute data');

                    break;
            }
        } catch (Exception $e) {
            return Response::redirectTo("/admin")->withFail(
                sprintf('Unexpected error: ' . $e->getMessage())
            );
        }

        return view('admin', [
            'weightsTableUrl' => env('SORT_CODE_IMPORT_WEIGHTS_URL'),
            'substitutesTableUrl' => env('SORT_CODE_IMPORT_SUBSTITUTES_URL')
        ]);
    }

    /**
     * Downloads the Sort code weights data file
     *
     * @throws RuntimeException
     */
    private function downloadWeights()
    {
        $outfile = env('PROJECT_DIRECTORY') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_DIR') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_WEIGHTS');

        return $this->downloadDataFile('Weights', 'weightsTableUrl', $outfile);
    }

    /**
     * Downloads the Sort code substitutes data file
     *
     * @throws RuntimeException
     */
    private function downloadSubstitutes()
    {
        $outfile = env('PROJECT_DIRECTORY') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_DIR') . DIRECTORY_SEPARATOR .
            env('SORT_CODE_IMPORT_SUBSTITUTES');

        return $this->downloadDataFile('Substitutes', 'substitutesTableUrl', $outfile);
    }

    /**
     * Downloads the data file
     *
     * @param string $dataType
     * @param string $param
     * @param string $outfile
     * @return bool
     */
    private function downloadDataFile($dataType, $param, $outfile)
    {
        // Get the URL for the data
        $params = request()->all();

        if (empty($params[$param])) {
            throw new RuntimeException("The url of the $dataType data file is required");
        }

        // Download the new data
        $data = file_get_contents($params[$param]);
        if (false === $data) {
            throw new RuntimeException("Error trying to download Sort Code $dataType data");
        }

        // Convert to an array and check we got something
        $dataArray = explode("\r\n", $data);
        if (is_array($dataArray) && count($dataArray) > 0) {

            if (file_exists($outfile)) {
                // Rename the current file out of the way
                rename($outfile, str_replace('.txt', '', $outfile) . date('Ymd_His') . '.txt');
            }

            // Output the new file data to the file system
            if (false === file_put_contents($outfile, implode(PHP_EOL, $dataArray), LOCK_EX)) {
                throw new RuntimeException("Error writing output file of Sort Code $dataType");
            }
        } else {
            throw new RuntimeException("No data obtained in download of Sort Code $dataType");
        }

        return true;
    }

    /**
     * Refreshes the Sort code weights data from the filesystem file
     *
     * @throws RuntimeException
     */
    public function refreshWeights()
    {
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
        (new Substitute())->refresh();

        return true;
    }
}
