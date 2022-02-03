<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FrameworkModel;

/**
 * Class HeartModel
 * 
 * Management of framework item likes
 */
class HeartModel extends Model
{
    use HasFactory;

    /**
     * Add heart to framework item
     *
     * @param $userId
     * @param $frameworkId
     * @return void
     * @throws Exception
     */
    public static function addHeart($userId, $frameworkId)
    {
        try {
            $heart = static::where('userId', '=', $userId)->where('frameworkId', '=', $frameworkId)->first();
            if ($heart) {
                throw new \Exception(__('app.already_hearted'));
            }

            $heart = new self;
            $heart->userId = $userId;
            $heart->frameworkId = $frameworkId;
            $heart->save();

            $user = User::where('id', '=', $userId)->first();

            $fw = FrameworkModel::where('id', '=', $frameworkId)->first();
            if ($fw) {
                $fw->hearts++;
                $fw->save();

                if ($userId !== $fw->userId) {
                    PushModel::addNotification(__('app.user_hearted_framework_short', ['name' => $user->username]), __('app.user_hearted_framework', ['name' => $user->username, 'item' => url('/framework/' . $frameworkId)]), 'PUSH_HEARTED', $fw->userId);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove heart from framework item
     * @param $userId
     * @param $frameworkId
     * @return void
     * @throws Exception
     */
    public static function removeHeart($userId, $frameworkId)
    {
        try {
            $heart = static::where('userId', '=', $userId)->where('frameworkId', '=', $entityId)->first();
            if (!$heart) {
                throw new \Exception(__('app.heart_not_exists'));
            }

            $heart->delete();

            $fw = FrameworkModel::where('id', '=', $frameworkId)->first();
            if ($fw) {
                $fw->hearts--;
                $fw->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if user has hearted a given framework item
     *
     * @param $userId
     * @param $frameworkId
     * @return bool
     * @throws Exception
     */
    public static function hasUserHearted($userId, $frameworkId)
    {
        try {
            $heart = static::where('userId', '=', $userId)->where('frameworkId', '=', $frameworkId)->first();

            return $heart !== null;
        } catch (\Execption $e) {
            throw $e;
        }
    }

    /**
     * Get all hearts of a framework item
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public static function getFromEntity($id)
    {
        try {
            return static::where('frameworkId', '=', $id)->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
