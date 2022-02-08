<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaptchaModel;
use App\Models\User;

/**
 * Class MemberController
 * 
 * Member specific route handling
 */
class MemberController extends Controller
{
    /**
     * View user profile
     * 
     * @param $ident
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfile($ident)
    {
        try {
            $user = User::getByUsername($ident);
            if (!$user) {
                $user = User::where('id', '=', $ident)->first();
                if (!$user) {
                    throw new \Exception(__('app.user_not_found'));
                }
            }

            if ($user->locked) {
                throw new \Exception(__('app.user_locked'));
            }

            return view('entities.profile', [
                'profile' => $user,
                'user' => User::getByAuthId(),
                'captcha' => CaptchaModel::createSum(session()->getId())
            ]);
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Check for username availability and identifier validity
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function usernameValidity()
    {
        try {
            $username = request('ident', '');

            $data = array(
                'username' => $username,
                'available' => User::getByUsername($username) == null,
                'valid' => User::isValidNameIdent($username)
            );

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Redirect to own profile
     * 
     * @return mixed
     */
    public function profile()
    {
        try {
            parent::validateLogin();

            return redirect('/user/' . auth()->id());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
