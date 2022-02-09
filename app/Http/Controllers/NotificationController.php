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
use App\Models\PushModel;

/**
 * Class NotificationController
 * 
 * Notification specific route handling
 */
class NotificationController extends Controller
{
    /**
     * Get notification list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $markSeen = (bool)request('mark', false);

            $notifications = PushModel::getUnseenNotifications(auth()->id(), $markSeen);
            foreach ($notifications as &$notification) {
                $notification->diffForHumans = $notification->created_at->diffForHumans();
            }

            return response()->json(array('code' => 200, 'data' => $notifications));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Mark notifications seen
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function seen()
    {
        try {
            PushModel::markSeen(auth()->id());

            return response()->json(array('code' => 200));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Fetch notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        try {
            $paginate = request('paginate', null);

            $notifications = PushModel::getNotifications(auth()->id(), env('APP_PUSHPACKLIMIT'), $paginate);
            foreach ($notifications as &$notification) {
                $notification->diffForHumans = $notification->created_at->diffForHumans();
            }

            return response()->json(array('code' => 200, 'data' => $notifications));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
