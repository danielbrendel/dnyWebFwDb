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
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Class TwitterModel
 * 
 * Twitter Bot manager
 */
class TwitterModel extends Model
{
    use HasFactory;

    /**
     * Post approved framework item info to Twitter
     * 
     * @param $framework
     * @return void
     * @throws \Exception
     */
    public static function postToTwitter($framework)
    {
        try {
            $connection = new TwitterOAuth(env('TWITTERBOT_APIKEY',), env('TWITTERBOT_APISECRET'), env('TWITTERBOT_ACCESS_TOKEN'), env('TWITTERBOT_ACCESS_TOKEN_SECRET'));  
            $media = $connection->upload('media/upload', ['media' => public_path() . '/gfx/logos/' . $framework->logo]);
 
            if (!isset($media->media_id_string)) {
                throw new \Exception('Failed to upload media to Twitter: ' . print_r($media, true));
            }

            $status = $framework->name . ': ' . $framework->summary . ' - by ' . $framework->creator;

            if (($framework->twitter !== null) && (is_string($framework->twitter)) && (strlen($framework->twitter) > 0)) {
                $status .= ' @' . $framework->twitter;
            }

            $status .= ' ' . url('/view/' . $framework->slug) . ' ' . env('TWITTERBOT_TAGS');

            $parameters = [
                'status' => $status,
                'media_ids' => implode(',', [$media->media_id_string])
            ];

            $result = $connection->post('statuses/update', $parameters);
            if (!isset($result->id)) {
                throw new \Exception('Failed to post status to Twitter: ' . print_r($result, true));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Twitter cronjob handler
     * 
     * @return void
     * @return array
     * @throws \Exception
     */
    public static function cronjob()
    {
        try {
            $item = FrameworkModel::where('twitter_posted', '=', false)->where('approved', '=', true)->orderBy('id', 'asc')->first();

            if ($item !== null) {
                TwitterModel::postToTwitter($item);

                $item->twitter_posted = true;
                $item->save();

                return $item->toArray();
            }

            return array();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
