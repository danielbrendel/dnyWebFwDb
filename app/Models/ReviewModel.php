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
use App\Models\User;

/**
 * Class ReviewModel
 * 
 * Review manager
 */
class ReviewModel extends Model
{
    use HasFactory;

    /**
     * Add review
     * 
     * @param $userId
     * @param $frameworkId
     * @param $text
     * @param $stars
     * @return void
     * @throws \Exception
     */
    public static function addReview($userId, $frameworkId, $text, $stars)
    {
        try {
            $user = User::where('id', '=', $userId)->where('locked', '=', false)->first();
            if (!$user) {
                throw new \Exception('User not valid');
            }

            $exists = static::where('userId', '=', $userId)->where('frameworkId', '=', $frameworkId)->count();
            if ($exists > 0) {
                throw new \Exception('There is already a review for this product by the user');
            }

            if (($stars < 1) || ($stars > 5)) {
                throw new \Exception('Stars must be a value from 1 to 5');
            }

            $item = new self();
            $item->userId = $userId;
            $item->frameworkId = $frameworkId;
            $item->content = $text;
            $item->stars = $stars;
            $item->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Query reviews of a framework item
     * 
     * @param $frameworkId
     * @param $paginate
     * @return mixed
     * @throws \Exception
     */
    public static function queryReviews($frameworkId, $paginate = null)
    {
        try {
            $query = static::where('frameworkId', '=', $frameworkId)->where('locked', '=', false);

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            return $query->orderBy('id', 'desc')->limit(env('APP_MAXQUERYCOUNT'))->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Query reviews of a specific user
     * 
     * @param $userId
     * @param $paginate
     * @return mixed
     * @throws \Exception
     */
    public static function queryUserReviews($userId, $paginate = null)
    {
        try {
            $query = static::where('userId', '=', $userId)->where('locked', '=', false);

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            return $query->orderBy('id', 'desc')->limit(env('APP_MAXQUERYCOUNT'))->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a review
     * 
     * @param $userId
     * @param $reviewId
     * @return void
     * @throws \Exception
     */
    public static function deleteReview($userId, $reviewId)
    {
        try {
            $user = User::where('id', '=', $userId)->where('locked', '=', false)->first();
            if (!$user) {
                throw new \Exception('Insufficient permissions');
            }

            $item = static::where('id', '=', $reviewId)->first();
            if (!$item) {
                throw new \Exception('Review not found');
            }

            if (($user->id != $item->userId) || (!$user->admin)) {
                throw new \Exception('Insufficient permissions');
            }

            $item->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get average stars value of framework item
     * 
     * @param $frameworkId
     * @return int
     * @throws \Exception;
     */
    public static function getAverageStars($frameworkId)
    {
        try {
            $count = static::where('frameworkId', '=', $frameworkId)->count();
            $stars = static::where('frameworkId', '=', $frameworkId)->sum('stars');

            return ($count > 0) ? $stars / $count : 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get review count of framework item
     * 
     * @param $frameworkId
     * @return int
     * @throws \Exception
     */
    public static function getReviewCount($frameworkId)
    {
        try {
            return static::where('frameworkId', '=', $frameworkId)->count();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
