<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\AppModel;
use App\Models\FrameworkModel;
use App\Models\UniqueViewModel;
use App\Models\GithubModel;
use App\Models\CaptchaModel;
use App\Models\ReviewModel;
use App\Models\ReportModel;
use App\Models\User;

/**
 * Class FrameworkController
 * 
 * Framework item specific route handling
 */
class FrameworkController extends Controller
{
    /**
     * Query framework item list
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function query()
    {
        try {
            $lang = request('lang', '_all_');
            $paginate = request('paginate', null);
            $text_search = request('text_search', null);
            $tag = request('tag', null);

            $data = FrameworkModel::queryFrameworks($lang, $paginate, $text_search, $tag);
            foreach ($data as &$item) {
                $user = User::where('id', '=', $item->userId)->first();
                $item->userData = new \stdClass();
                $item->userData->id = $user->id;
                $item->userData->username = $user->username;

                $item->views = AppModel::countAsString(UniqueViewModel::viewForItem($item->id));
                $item->avg_stars = ReviewModel::getAverageStars($item->id);
                $item->review_count = ReviewModel::getReviewCount($item->id);
                $item->tags = explode(' ', $item->tags);
            }

            return response()->json(array('code' => 200, 'data' => $data->toArray()));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Query framework item list of a specific user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryUser()
    {
        try {
            $userId = request('user');
            $paginate = request('paginate', null);

            $user = User::where('id', '=', $userId)->where('locked', '=', false)->first();
            if (!$user) {
                throw new \Exception('User not found: ' . $user);
            }

            $data = FrameworkModel::queryUserFrameworks($user->id, $paginate);
            foreach ($data as &$item) {
                $user = User::where('id', '=', $item->userId)->first();
                $item->userData = new \stdClass();
                $item->userData->id = $user->id;
                $item->userData->username = $user->username;

                $item->views = AppModel::countAsString(UniqueViewModel::viewForItem($item->id));
                $item->avg_stars = ReviewModel::getAverageStars($item->id);
                $item->review_count = ReviewModel::getReviewCount($item->id);
                $item->tags = explode(' ', $item->tags);
            }

            return response()->json(array('code' => 200, 'data' => $data->toArray()));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Query framework item reviews
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function queryReviews()
    {
        try {
            $frameworkId = request('frameworkId');
            $paginate = request('paginate', null);

            $data = ReviewModel::queryReviews($frameworkId, $paginate);
            foreach ($data as &$item) {
                $user = User::where('id', '=', $item->userId)->first();

                $item->userData = new \stdClass();
                $item->userData->id = $user->id;
                $item->userData->username = $user->username;
                $item->userData->avatar = $user->avatar;
            }

            $review_count = ReviewModel::getReviewCount($frameworkId);

            return response()->json(array('code' => 200, 'data' => $data->toArray(), 'count' => $review_count));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View specific framework item
     * 
     * @param $framework
     * @return mixed
     */
    public function view($framework)
    {
        try {
            $check_flags = true;
            $viewer = User::getByAuthId();
            if (($viewer) && ($viewer->admin)) {
                $check_flags = false;
            }

            $item = FrameworkModel::getBySlug($framework, $check_flags);
            if (!$item) {
                $item = FrameworkModel::where('id', '=', $framework);
                if ($check_flags) {
                    $item->where('locked', '=', false)->where('approved', '=', true);
                }
                $item = $item->first();
                if (!$item) {
                    throw new \Exception(__('app.framework_not_found'));
                }
            }

            $user = User::where('id', '=', $item->userId)->first();
            $item->userData = new \stdClass();
            $item->userData->id = $user->id;
            $item->userData->username = $user->username;

            $item->views = AppModel::countAsString(UniqueViewModel::viewForItem($item->id));
            $item->github = GithubModel::queryRepoInfo($item->github);
            $item->github->last_commit_diff = Carbon::parse($item->github->pushed_at)->diffForHumans();
            $item->github->commit_day_count = Carbon::parse($item->github->pushed_at)->diff(Carbon::now())->days;
            $item->github->stargazers_count = AppModel::countAsString($item->github->stargazers_count);
            $item->github->forks_count = AppModel::countAsString($item->github->forks_count);
            $item->tags = explode(' ', $item->tags);
            $item->avg_stars = ReviewModel::getAverageStars($item->id);
            $item->review_count = ReviewModel::getReviewCount($item->id);
            
            $others = FrameworkModel::queryRandom($item->id, $item->langId, env('APP_QUERYRANDOMCOUNT'));
            foreach ($others as &$other) {
                $user = User::where('id', '=', $other->userId)->first();
                $other->userData = new \stdClass();
                $other->userData->id = $user->id;
                $other->userData->username = $user->username;

                $other->views = AppModel::countAsString(UniqueViewModel::viewForItem($other->id));
                $other->avg_stars = ReviewModel::getAverageStars($item->id);
                $other->review_count = ReviewModel::getReviewCount($item->id);
                $other->tags = explode(' ', $other->tags);
            }

            return view('entities.framework', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'user' => User::getByAuthId(),
                'framework' => $item,
                'others' => $others
            ]);
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * View submit form
     * 
     * @return mixed
     */
    public function viewSubmit()
    {
        try {
            parent::validateLogin();

            $user = User::getByAuthId();

            return view('home.submit', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'user' => $user,
                'metro' => true
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', $e->getMessage());
        }
    }

    /**
     * Submit framework item
     * 
     * @return mixed
     */
    public function submit()
    {
        try {
            parent::validateLogin();

            $attr = request()->validate([
                'name' => 'required',
                'summary' => 'required|max:120',
                'lang' => 'required|numeric',
                'description' => 'required',
                'creator' => 'required',
                'tags' => 'nullable',
                'github' => 'required',
                'twitter' => 'nullable',
                'website' => 'nullable'
            ]);

            FrameworkModel::addFramework($attr);

            return redirect('/')->with('success', __('app.framework_submitted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View edit form
     * 
     * @param $id
     * @return mixed
     */
    public function viewEdit($id)
    {
        try {
            parent::validateLogin();

            $user = User::getByAuthId();
            
            $query = FrameworkModel::where('id', '=', $id);

            if (!$user->admin) {
                $query->where('userId', '=', $user->id)->where('locked', '=', false);
            }

            $framework = $query->first();

            if (!$framework) {
                throw new \Exception('Invalid framework item or insufficient permissions: ' . $id);
            }

            return view('home.edit', [
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'user' => $user,
                'metro' => true,
                'framework' => $framework
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update framework with edited data
     * 
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        try {
            parent::validateLogin();

            $attr = request()->validate([
                'summary' => 'required|max:120',
                'lang' => 'required|numeric',
                'description' => 'required',
                'creator' => 'required',
                'tags' => 'nullable',
                'github' => 'required',
                'twitter' => 'nullable',
                'website' => 'nullable'
            ]);

            $user = User::getByAuthId();

            $query = FrameworkModel::where('id', '=', $id);

            if (!$user->admin) {
                $query->where('userId', '=', $user->id)->where('locked', '=', false);
            }

            $framework = $query->first();

            if (!$framework) {
                throw new \Exception('Invalid framework item or insufficient permissions: ' . $id);
            }

            $attr['name'] = $framework->name;

            FrameworkModel::editFramework($id, $attr);

            return redirect('/view/' . $framework->slug)->with('success', __('app.framework_saved_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Report a framework item
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportFramework($id)
    {
        try {
            parent::validateLogin();

            ReportModel::addReport(auth()->id(), $id, 'ENT_FRAMEWORK');

            return response()->json(array('code' => 200, 'msg' => __('app.report_successful')));
        } catch(\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Report a review
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportReview($id)
    {
        try {
            parent::validateLogin();

            ReportModel::addReport(auth()->id(), $id, 'ENT_REVIEW');

            return response()->json(array('code' => 200, 'msg' => __('app.report_successful')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete a review
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReview($id)
    {
        try {
            parent::validateLogin();

            $user = User::getByAuthId();

            $item = ReviewModel::where('id', '=', $id)->first();
            if ($item->locked) {
                throw new \Exception('Item is locked');
            }

            if ((!$item->userId !== $user->id) && (!$user->admin)) {
                throw new \Exception('Insufficient permissions');
            }

            $item->delete();

            return response()->json(array('code' => 200, 'msg' => __('app.removal_successful')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete a framework item
     * 
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFramework($id)
    {
        try {
            parent::validateLogin();

            $user = User::getByAuthId();

            $item = ReviewModel::where('id', '=', $id)->first();
            if ($item->locked) {
                throw new \Exception('Item is locked');
            }

            if ((!$item->userId !== $user->id) && (!$user->admin)) {
                throw new \Exception('Insufficient permissions');
            }

            AppModel::deleteEntity($item->id, 'ENT_FRAMEWORK');

            return response()->json(array('code' => 200, 'msg' => __('app.removal_successful')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
