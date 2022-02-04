<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\FrameworkModel;
use App\Models\UniqueViewModel;
use App\Models\GithubModel;
use App\Models\User;

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
            $sorting = request('sorting', 'latest');
            $paginate = request('paginate', null);
            $text_search = request('text_search', null);
            $tag = request('tag', null);

            $data = FrameworkModel::queryFrameworks($lang, $sorting, $paginate, $text_search, $tag);
            foreach ($data as &$item) {
                $user = User::where('id', '=', $item->userId)->first();
                $item->userData = new \stdClass();
                $item->userData->id = $user->id;
                $item->userData->username = $user->username;

                $item->views = UniqueViewModel::viewCountAsString(UniqueViewModel::viewForItem($item->id));
            }

            return response()->json(array('code' => 200, 'data' => $data->toArray()));
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
            $item = FrameworkModel::getBySlug($framework);
            if (!$item) {
                $item = FrameworkModel::where('id', '=', $framework)->first();
                if (!$item) {
                    throw new \Exception(__('app.framework_not_found'));
                }
            }

            $user = User::where('id', '=', $item->userId)->first();
            $item->userData = new \stdClass();
            $item->userData->id = $user->id;
            $item->userData->username = $user->username;

            $item->views = UniqueViewModel::viewCountAsString(UniqueViewModel::viewForItem($item->id));
            $item->github = GithubModel::queryRepoInfo($item->github);
            $item->github->last_commit_diff = Carbon::parse($item->github->pushed_at)->diffForHumans();
            $item->github->commit_day_count = Carbon::parse($item->github->pushed_at)->diff(Carbon::now())->days;
            
            $user = User::getByAuthId();
            
            $others = FrameworkModel::queryRandom($item->id, $item->langId, env('APP_QUERYRANDOMCOUNT'));
            foreach ($others as &$other) {
                $user = User::where('id', '=', $other->userId)->first();
                $other->userData = new \stdClass();
                $other->userData->id = $user->id;
                $other->userData->username = $user->username;

                $other->views = UniqueViewModel::viewCountAsString(UniqueViewModel::viewForItem($other->id));
            }

            return view('framework', [
                'user' => $user,
                'framework' => $item,
                'others' => $others
            ]);
        } catch (\Exception $e) {
            dd($e);
            return back()->with('flash.error', $e->getMessage());
        }
    }
}
