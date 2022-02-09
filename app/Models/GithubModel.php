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
 * Class GithubModel
 * 
 * GitHub query manager
 */
class GithubModel extends Model
{
    use HasFactory;

    const GITHUB_API_URL = 'https://api.github.com/repos/';

    /**
     * Query GitHub repo details
     * 
     * @param $repo
     * @return object
     * @throws \Exception
     */
    public static function queryRepoInfo($repo)
    {
        try {
            if (strpos($repo, '/') === false) {
                throw new \Exception('Invalid repo specifier: ' . $repo);
            }

            $curl = curl_init();

            $headers = [
                'User-Agent: ' . env('APP_NAME'),
                'Accept: application/vnd.github.v3+json'
            ];

            curl_setopt($curl, CURLOPT_URL, self::GITHUB_API_URL . $repo);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($curl);
            $result_data = json_decode($result);
            
            if ((!isset($result_data->full_name)) || ($result_data->full_name !== $repo)) {
                throw new \Exception('Failed to query repository');
            }

            curl_close($curl);

            return $result_data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
