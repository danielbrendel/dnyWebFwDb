<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MainController extends Controller
{
    public function index()
    {
        return view('home');
    }

    /**
     * Perform user login
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        try {
            $attr = request()->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            User::login($attr['email'], $attr['password']);

            return redirect('/')->with('flash.success', __('app.login_successful'));
        } catch (\Exception $e) {
            return redirect('/')->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Perform user registration
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        try {
            $attr = request()->validate([
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $id = User::register($attr);

            return redirect('/')->with('flash.success', __('app.registration_success', ['id' => $id]));
        } catch (\Exception $e) {
            return redirect('/')->with('flash.error', $e->getMessage());
        }
    }

    /**
     * View password reset form
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewReset()
    {
        return view('home.pwreset', [
            'hash' => request('hash', ''),
            'captchadata' => $this->generateCaptcha()
        ]);
    }

    /**
     * Send email with password recovery link to user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws Throwable
     */
    public function recover()
    {
        $attr = request()->validate([
            'email' => 'required|email'
        ]);

        try {
            User::recover($attr['email']);

            return back()->with('success', __('app.pw_recovery_ok'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reset password
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset()
    {
        $attr = request()->validate([
            'password' => 'required',
            'password_confirm' => 'required'
        ]);

        $hash = request('hash');

        try {
            User::reset($attr['password'], $attr['password_confirm'], $hash);

            return redirect('/')->with('success', __('app.password_reset_ok'));
        } catch (Exception $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    /**
     * Resend confirmation link
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend($id)
    {
        try {
            User::resend($id);

            return back()->with('success', __('app.resend_ok', ['id' => $id]));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm account
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirm()
    {
        $hash = request('hash');

        try {
            User::confirm($hash);

            return redirect('/')->with('success', __('app.register_confirmed_ok'));
        } catch (Exception $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    /**
     * Perform logout
     * 
     * @return mixed
     */
    public function logout()
    {
        try {
            \Auth::logout();

            return redirect('/')->with('flash.success', __('app.logout_successful'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
