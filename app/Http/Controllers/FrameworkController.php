<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrameworkModel;
use App\Models\User;
use App\Models\UniqueViewModel;

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
}
