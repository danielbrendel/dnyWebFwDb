<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppModel;
use App\Models\FrameworkModel;
use App\Models\ReportModel;
use App\Models\CaptchaModel;
use App\Models\ImageModel;
use App\Models\User;

/**
 * Class AdminController
 * 
 * Administrative route management
 */
class AdminController extends Controller
{
    /**
     * Validate admin access here
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function($request, $next){
            try {
                parent::validateAdmin();
            } catch (\Exception $e) {
                abort(403);
            }

            return $next($request);
        });
    }

     /**
     * Show index page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $approvals = FrameworkModel::where('approved', '=', false)->orderBy('id', 'asc')->limit(env('APP_APPROVALFETCHCOUNT'))->get();
        foreach ($approvals as &$approval) {
            $user = User::where('id', '=', $approval->userId)->first();

            $approval->userData = new \stdClass();
            $approval->userData->id = $user->id;
            $approval->userData->username = $user->username;
            $approval->userData->avatar = $user->avatar;
        }

        $reports = array(
            'frameworks' => ReportModel::getReportPack('ENT_FRAMEWORK'),
            'users' => ReportModel::getReportPack('ENT_USER'),
            'reviews' => ReportModel::getReportPack('ENT_REVIEW')
          );

        return view('admin.index', [
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'settings' => AppModel::getAppSettings(),
            'approvals' => $approvals,
            'reports' => $reports,
            'user' => User::getByAuthId(),
            'metro' => true
        ]);
    }

    /**
     * Save about content
     * 
     * @return mixed
     */
    public function saveAboutContent()
    {
        try {
            $attr = request()->validate([
                'about' => 'required'
            ]);

            AppModel::saveSetting('about', $attr['about']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save cookie consent
     * 
     * @return mixed
     */
    public function saveCookieConsent()
    {
        try {
            $attr = request()->validate([
                'cookieconsent' => 'required'
            ]);

            AppModel::saveSetting('cookie_consent', $attr['cookieconsent']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save registration info
     * 
     * @return mixed
     */
    public function saveRegInfo()
    {
        try {
            $attr = request()->validate([
                'reginfo' => 'required'
            ]);

            AppModel::saveSetting('reg_info', $attr['reginfo']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save ToS content
     * 
     * @return mixed
     */
    public function saveTosContent()
    {
        try {
            $attr = request()->validate([
                'tos' => 'required'
            ]);

            AppModel::saveSetting('tos', $attr['tos']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save imprint content
     * 
     * @return mixed
     */
    public function saveImprintContent()
    {
        try {
            $attr = request()->validate([
                'imprint' => 'required'
            ]);

            AppModel::saveSetting('imprint', $attr['imprint']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save head code content
     * 
     * @return mixed
     */
    public function saveHeadCode()
    {
        try {
            $attr = request()->validate([
                'headcode' => 'required'
            ]);

            AppModel::saveSetting('head_code', $attr['headcode']);

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get user details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userDetails()
    {
        try {
            $ident = request('ident');

            $user = User::where('id', '=', $ident)->first();
            if (!$user) {
                $user = User::getByUsername($ident);
                if (!$user) {
                    $user = User::getByEmail($ident);
                    if (!$user) {
                        return response()->json(array('code' => 404, 'msg' => __('app.user_not_found')));
                    }
                }
            }

            return response()->json(array('code' => 200, 'data' => $user));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Save user data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userSave()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'username' => 'required',
                'email' => 'required|email',
                'locked' => 'nullable|numeric',
                'admin' => 'nullable|numeric'
            ]);

            $user = User::where('id', '=', $attr['id'])->first();
            if (!$user) {
                throw new \Exception(__('app.user_not_found'));
            }

            $user->username = $attr['username'];
            $user->email = $attr['email'];
            $user->locked = (isset($attr['locked'])) ? (bool)$attr['locked'] : false;
            $user->admin = (isset($attr['admin'])) ? (bool)$attr['admin'] : false;
            $user->save();

            return back()->with('flash.success', __('app.data_saved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reset user password
     * 
     * @param $id
     * @return mixed
     */
    public function userResetPassword($id)
    {
        try {
            $user = User::where('id', '=', $id)->first();

            User::recover($user->email);

            return back()->with('flash.success', __('app.pw_recovery_ok'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Lock user account
     * 
     * @param $id
     * @return mixed
     */
    public function lockUser($id)
    {
        try {
            ReportModel::setSafe($id);

            $user = User::where('id', '=', $id)->first();
            $user->locked = true;
            $user->save();

            return back()->with('flash.success', __('app.user_locked'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Set user safe
     * 
     * @param $id
     * @return mixed
     */
    public function setUserSafe($id)
    {
        try {
            ReportModel::setSafe($id);

            return back()->with('flash.success', __('app.user_now_safe'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete user account
     * 
     * @param $id
     * @return mixed
     */
    public function userDelete($id)
    {
        try {
            User::deleteAccount($id);

            return back()->with('flash.success', __('app.account_deleted'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Save logo
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveLogo()
    {
        try {
            $attr = request()->validate([
               'logo' => 'required|file'
            ]);

            $av = request()->file('logo');
            if ($av != null) {
                if ($av->getClientOriginalExtension() !== 'png') {
                    return back()->with('error', __('app.not_a_png_file'));
                }

                $tmpName = md5(random_bytes(55));

                $av->move(public_path() . '/', $tmpName . '.' . $av->getClientOriginalExtension());

                list($width, $height) = getimagesize(base_path() . '/public/' . $tmpName . '.' . $av->getClientOriginalExtension());

                $avimg = imagecreatetruecolor(128, 128);
                if (!$avimg)
                    throw new \Exception('imagecreatetruecolor() failed');

                $srcimage = null;
                $newname =  'logo.' . $av->getClientOriginalExtension();
                switch (ImageModel::getImageType($av->getClientOriginalExtension(), public_path() . '/' . $tmpName)) {
                    case IMAGETYPE_PNG:
                        $srcimage = imagecreatefrompng(public_path() . '/' . $tmpName . '.' . $av->getClientOriginalExtension());
                        imagecopyresampled($avimg, $srcimage, 0, 0, 0, 0, 128, 128, $width, $height);
                        imagepng($avimg, public_path() . '/' . $newname);
                        break;
                    default:
                        return back()->with('error', __('app.not_a_png_file'));
                        break;
                }

                unlink(public_path() . '/' . $tmpName . '.' . $av->getClientOriginalExtension());

                return back()->with('flash.success', __('app.data_saved'));
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve framework item
     * 
     * @param $id
     * @return mixed
     */
    public function approveFramework($id)
    {
        try {
            $item = FrameworkModel::where('id', '=', $id)->first();
            if (!$item) {
                throw new \Exception('Framework item not found: ' . $id);
            }

            $item->approved = true;
            $item->save();

            return back()->with('flash.success', __('app.framework_approved'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Decline framework item
     * 
     * @param $id
     * @return mixed
     */
    public function declineFramework($id)
    {
        try {
            $item = FrameworkModel::where('id', '=', $id)->first();
            if (!$item) {
                throw new \Exception('Framework item not found: ' . $id);
            }

            $item->delete();

            return back()->with('flash.success', __('app.framework_declined'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Lock entity
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lockEntity()
    {
        try {
            $id = request('id');
            $type = request('type');

            AppModel::lockEntity($id, $type);

            return back()->with('flash.success', __('app.entity_locked'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete entity
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteEntity()
    {
        try {
            $id = request('id');
            $type = request('type');

            AppModel::deleteEntity($id, $type);

            return back()->with('flash.success', __('app.entity_deleted'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Set entity safe
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setSafeEntity()
    {
        try {
            $id = request('id');
            $type = request('type');

            AppModel::setEntitySafe($id, $type);

            return back()->with('flash.success', __('app.entity_set_safe'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
