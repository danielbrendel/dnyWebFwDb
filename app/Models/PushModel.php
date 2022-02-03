<?php

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
            $types = array('PUSH_WELCOME', 'PUSH_VISITED', 'PUSH_LIKED', 'PUSH_MESSAGED', 'PUSH_APPROVAL', 'PUSH_GUESTBOOK');
            if (!in_array($type, $types)) {
                throw new \Exception('Invalid notification type: ' . $type);
            }
        } catch (Exception $e) {
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

            $entry = new PushModel();
            $entry->type = $type;
            $entry->shortMsg = $shortMsg;
            $entry->longMsg = $longMsg;
            $entry->seen = false;
            $entry->userId = $userId;
            $entry->save();

            if (env('FIREBASE_ENABLE', false)) {
                $user = User::get($userId);
                if (($user) && (is_string($user->device_token)) && (strlen($user->device_token) > 0)) {
                    static::sendCloudNotification($shortMsg, $longMsg, $user->device_token);
                }
            }
        } catch (Exception $e) {
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
            $items = PushModel::where('userId', '=', $userId)->where('seen', '=', false)->get();

            if ($markSeen) {
                foreach ($items as $item) {
                    $item->seen = true;
                    $item->save();
                }
            }

            return $items;
        } catch (Exception $e) {
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
            $count = PushModel::where('userId', '=', $userId)->where('seen', '=', false)->count();

            return $count > 0;
        } catch (Exception $e) {
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
            $rowset = PushModel::where('userId', '=', $userId)->where('seen', '=', true);

            if ($paginate !== null) {
                $rowset->where('id', '<', $paginate);
            }

            return $rowset->orderBy('id', 'desc')->limit($limit)->get();
        } catch (Exception $e) {
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
            $items = PushModel::where('userId', '=', $userId)->where('seen', '=', false)->get();

            foreach ($items as $item) {
                $item->seen = true;
                $item->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send cloud notification to Google Firebase
     * 
     * @param $title
     * @param $body
     * @param $device_token
     * @return void
     * @throws \Exception
     */
    private static function sendCloudNotification($title, $body, $device_token)
    {
        try {
            $curl = curl_init();

            $headers = [
                'Content-Type: application/json',
                'Authorization: key=' . env('FIREBASE_KEY')
            ];

            $data = [
                'to' => $device_token,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'icon' => asset('logo.png')
                ]
            ];

            curl_setopt($curl, CURLOPT_URL, env('FIREBASE_ENDPOINT'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

            $result = curl_exec($curl);
            $result_data = json_decode($result);
            if ((!isset($result_data->success)) || (!$result_data->success)) {
                //throw new \Exception('Failed to deliver Firebase cloud message');
            }

            curl_close($curl);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
