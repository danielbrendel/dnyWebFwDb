<?php

/*
    WebframeworkDB (dnyWebFwDb) developed by Daniel Brendel

    (C) 2022 by Daniel Brendel

    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PushModel
 *
 * Represents the push notifications interface
 */
class PushModel extends Model
{
    use HasFactory;

    /**
     * Validate notification type
     *
     * @param $type
     * @throws Exception
     */
    private static function validatePushType($type)
    {
        try {
            $types = array('PUSH_WELCOME', 'PUSH_APPROVAL', 'PUSH_REVIEWED');
            if (!in_array($type, $types)) {
                throw new \Exception('Invalid notification type: ' . $type);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add a notification to the list
     *
     * @param $shortMsg
     * @param $longMsg
     * @param $type
     * @param int $userId
     * @return void
     * @throws Exception
     */
    public static function addNotification($shortMsg, $longMsg, $type, $userId)
    {
        try {
            static::validatePushType($type);

            $entry = new self();
            $entry->type = $type;
            $entry->shortMsg = $shortMsg;
            $entry->longMsg = $longMsg;
            $entry->seen = false;
            $entry->userId = $userId;
            $entry->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all unseen notifications and mark them as seen
     *
     * @param int $userId The ID of the user
     * @param bool $markSeen If notifications shall be marked as seen
     * @return mixed Items or null if non exist
     * @throws Exception
     */
    public static function getUnseenNotifications($userId, $markSeen = true)
    {
        try {
            $items = static::where('userId', '=', $userId)->where('seen', '=', false)->get();

            if ($markSeen) {
                foreach ($items as $item) {
                    $item->seen = true;
                    $item->save();
                }
            }

            return $items;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Indicate if there are unseen notifications
     *
     * @param $userId
     * @return bool
     * @throws Exception
     */
    public static function hasUnseenNotifications($userId)
    {
        try {
            $count = static::where('userId', '=', $userId)->where('seen', '=', false)->count();

            return $count > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get notifications of user
     *
     * @param $userId
     * @param $limit
     * @param null $paginate
     * @return mixed
     * @throws Exception
     */
    public static function getNotifications($userId, $limit, $paginate = null)
    {
        try {
            $rowset = static::where('userId', '=', $userId)->where('seen', '=', true);

            if ($paginate !== null) {
                $rowset->where('id', '<', $paginate);
            }

            return $rowset->orderBy('id', 'desc')->limit($limit)->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Mark unseen notifications as seen
     *
     * @param $userId
     * @throws \Exception
     */
    public static function markSeen($userId)
    {
        try {
            $items = static::where('userId', '=', $userId)->where('seen', '=', false)->get();

            foreach ($items as $item) {
                $item->seen = true;
                $item->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
