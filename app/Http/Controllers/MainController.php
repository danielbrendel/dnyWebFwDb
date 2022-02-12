<?php

/*
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaptchaModel;
use App\Models\AppModel;
use App\Models\TwitterModel;
use App\Models\User;

/**
 * Class MainController
 * 
 * General route handler
 */
class MainController extends Controller
{
    /**
     * Show index page
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = User::getByAuthId();

        return view('home.home', [
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'user' => $user,
            'fw_item_filter' => true
        ]);
    }

    /**
     * Show imprint page
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function imprint()
    {
        $user = User::getByAuthId();

        return view('home.imprint', [
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'user' => $user,
            'imprint' => AppModel::getImprint()
        ]);
    }

    /**
     * Show ToS page
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tos()
    {
        $user = User::getByAuthId();

        return view('home.tos', [
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'user' => $user,
            'tos' => AppModel::getTermsOfService()
        ]);
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
                'password' => 'required',
                'captcha' => 'required|numeric'
            ]);

            $id = User::register($attr);

            return redirect('/')->with('success', __('app.register_confirm_email', ['id' => $id]));
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
            'captcha' => CaptchaModel::createSum(session()->getId())
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

    /**
     * Perform Twitter cronjob
     *
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    public function cronjob_twitter($password)
    {
        try {
            if (!env('TWITTERBOT_ENABLE', false)) {
                throw new \Exception('Twitter Bot is currently disabled');
            }

            if ($password !== env('APP_CRONPW')) {
                return response()->json(array('code' => 403));
            }

            $data = TwitterModel::cronjob();

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Perform newsletter cronjob
     *
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    public function cronjob_newsletter($password)
    {
        try {
            if ($password !== env('APP_CRONPW')) {
                return response()->json(array('code' => 403));
            }

            $data = AppModel::newsletterJob();

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
