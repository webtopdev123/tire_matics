<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->cookie('remember_me_token')) {
            $token = $request->cookie('remember_me_token');

            // Attempt to authenticate the user using the token
            $user = User::where('remember_token', $token)->first();

            if ($user) {
                Auth::login($user);

                if ($user->role_id == 1) {
                    $user->merchant_id = 0;
                    $user->save();
                    return redirect()->route('merchant.index');
                }

                return redirect()->route('dashboard.index');
            }
        }

        $pageConfigs = ['myLayout' => 'blank'];
        return view('content.authentications.auth-login', ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $user = $request->user();

        if ($user) {
          $user->tokens()->where('name', 'Personal Access Token')->delete();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $cookie = Cookie::forget('remember_me_token');

        return redirect()->route('auth-login')->withCookie($cookie);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = Auth::user();

        return response()->json($user);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('name', 'password');

        // if (!Auth::attempt($credentials, $request->remember_me)) {
        if (!Auth::attempt($credentials, true)) {
            Session::flash('message', 'Invalid login detail');
            return redirect()->route('auth-login');
        }

        $user = $request->user();

        if ($user->status !== 1) {
            Session::flash('message', 'Your account status is pending');
            Auth::logout();
            return redirect()->route('auth-login');
        }


        if ($request->remember_me == 'on') {
            $cookie = cookie(
                'remember_me_token',
                $user->remember_token,
                30 * 24 * 60,
            );
        } else {
            $endOfDayTimestamp = strtotime('today 23:59:59');
            $minutesUntilEndOfDay = ($endOfDayTimestamp - time()) / 60;

            $cookie = cookie(
                'remember_me_token',
                $user->remember_token,
                $minutesUntilEndOfDay,
            );
        }

        if ($user->role_id == 1) {
            $user->merchant_id = 0;
            $user->save();

            return redirect()
                ->route('merchant.index')
                ->withCookie($cookie);
        }

        return redirect()
            ->route('dashboard.index')
            ->withCookie($cookie);

        // if ($request->remember_me == 'on') {
        //     // Set a cookie for 30 days with the access token
        //     $cookie = cookie(
        //         'remember_me_token',
        //         $user->remember_token,
        //         30 * 24 * 60, // 30 days in minutes
        //     );

        //     if ($user->role_id == 1) {
        //         $user->merchant_id = 0;
        //         $user->save();

        //         return redirect()
        //             ->route('merchant.index')
        //             ->withCookie($cookie);
        //     }

        //     return redirect()
        //         ->route('dashboard.index')
        //         ->withCookie($cookie);
        // } else {
        //     // Check if the remember me token exists and delete it
        //     if ($request->cookie('remember_me_token')) {
        //         if ($user->role_id == 1) {
        //             $user->merchant_id = 0;
        //             $user->save();

        //             return redirect()
        //                 ->route('merchant.index')
        //                 ->withCookie(cookie()->forget('remember_me_token'));
        //         }

        //         return redirect()
        //             ->route('dashboard.index')
        //             ->withCookie(cookie()->forget('remember_me_token'));
        //     } else {
        //         if ($user->role_id == 1) {
        //             $user->merchant_id = 0;
        //             $user->save();

        //             return redirect()->route('merchant.index');
        //         }

        //         return redirect()->route('dashboard.index');
        //     }
        // }
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
}
