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
use App\Models\ReviewModel;
use App\Models\ReportModel;
use App\Models\AppModel;
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

    /**
     * Save profile data
     * 
     * @return mixed
     */
    public function saveProfile()
    {
        try {
            parent::validateLogin();

            $attr = request()->validate([
                'location' => 'nullable',
                'bio' => 'nullable',
                'twitter' => 'nullable',
                'password' => 'nullable',
                'password_confirmation' => 'nullable',
                'email' => 'nullable|email',
                'newsletter' => 'nullable|numeric'
            ]);

            if (!isset($attr['newsletter'])) {
                $attr['newsletter'] = 0;
            }

            User::saveUserProfile(auth()->id(), $attr);

            return back()->with('flash.success', __('app.profile_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Query reviews of a specific user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryReviews()
    {
        try {
            $userId = request('userId');
            $paginate = request('paginate', null);

            $data = ReviewModel::queryUserReviews($userId, $paginate);
            foreach ($data as &$item) {
                $user = User::where('id', '=', $item->userId)->first();

                $item->userData = new \stdClass();
                $item->userData->id = $user->id;
                $item->userData->username = $user->username;
                $item->userData->avatar = $user->avatar;
            }

            return response()->json(array('code' => 200, 'data' => $data->toArray()));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Report a user
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportUser($id)
    {
        try {
            parent::validateLogin();

            ReportModel::addReport(auth()->id(), $id, 'ENT_USER');

            return response()->json(array('code' => 200, 'msg' => __('app.report_successful')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete user account
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUserAccount()
    {
        try {
            parent::validateLogin();

            $password = request('password');
            $user = User::getByAuthId();

            if (!app('hash')->check($password, $user->password)) {
                throw new \Exception(__('app.password_mismatch'));
            }

            AppModel::deleteEntity(auth()->id(), 'ENT_USER');

            return response()->json(array('code' => 200, 'msg' => __('app.deleted_account_successfully')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
