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
}
