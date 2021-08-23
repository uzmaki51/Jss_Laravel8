<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'account';
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'account' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('account', $request->input('account'))->first();

        if (auth()->guard('web')->attempt(['account' => $request->input('account'), 'password' => $request->input('password')])) {

            $new_session_id = \Session::getId(); //get new session_id after user sign in

            if ($user->session_id != '') {
                $last_session = \Session::getHandler()->read($user->session_id);

                if ($last_session) {
                    if (\Session::getHandler()->destroy($user->session_id)) {

                    }
                }
            }

            User::where('id', $user->id)->update(['session_id' => $new_session_id]);

            $user = auth()->guard('web')->user();

            return redirect($this->redirectTo);
        }
        \Session::put('login_error', 'Your account and password wrong!!');
        //return back();
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
