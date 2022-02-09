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
 * Class CaptchaModel
 *
 * Captcha manager
 */
class CaptchaModel extends Model
{
    use HasFactory;

    /**
     * Query sum of hash
     *
     * @param string $hash The input hash
     * @return string|bool The found sum or false on failure
     */
    public static function querySum($hash)
    {
        $result = static::where('hash', '=', $hash)->first();
        if (!$result)
            return false;

        return $result->sum;
    }

    /**
     * Create sum for hash
     *
     * @param string $hash The input hash
     * @return array An array containing both summands
     */
    public static function createSum($hash)
    {
        $result = [
            rand(0, 10),
            rand(0, 10)
        ];

        $entry = static::where('hash', '=', $hash)->first();
        if (!$entry) {
            $entry = new self();
        }

        $entry->hash = $hash;
        $entry->sum = strval($result[0] + $result[1]);
        $entry->save();

        return $result;
    }
}

