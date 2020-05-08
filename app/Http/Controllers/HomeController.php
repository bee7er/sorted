<?php

namespace App\Http\Controllers;

use App\AccountValidatorManager;
use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use RuntimeException;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Show the application dashboard to the user.
	 *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
	{
		$loggedIn = false;
		if ($this->auth->check()) {
			$loggedIn = true;
		}

        $sortCode = '';
        $accountNumber = '';

		return view('welcome', compact('sortCode', 'accountNumber', 'loggedIn'));
	}

	/**
	 * Show the application dashboard to the user.
	 *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function validateForm(Request $request)
	{
        try {
            $loggedIn = false;
            if ($this->auth->check()) {
                $loggedIn = true;
            }

            $sortCode = $request->get('sortCode');
            $accountNumber = $request->get('accountNumber');

            // Clear all messages
            Session::forget(['success', 'fail']);

            $accountValidatorManager = new AccountValidatorManager();
            $result = $accountValidatorManager->validateSortCodeAccountNumber($sortCode, $accountNumber);
            if ($result['valid']) {
				return Response::redirectTo("/")->withSuccess($result['message']);
            } else {
				return Response::redirectTo("/")->withFail($result['message']);
            }

        } catch (RuntimeException $e) {
            return Response::redirectTo("/")->withFail(
                sprintf("An error was detected validating data '%s'", $e->getMessage())
            );
        } catch (Exception $e) {
            return Response::redirectTo("/")->withFail(
                sprintf("A general error was detected validating data '%s'", $e->getMessage())
            );
        }

		return view('welcome', compact('sortCode', 'accountNumber', 'loggedIn'));
	}
}
