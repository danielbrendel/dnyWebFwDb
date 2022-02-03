<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueViewModel extends Model
{
    use HasFactory;

    const COUNT_MILLION = 1000000;
    const COUNT_HUNDREDTHOUSAND = 100000;
    const COUNT_TENTHOUSAND = 10000;
    const COUNT_THOUSAND = 1000;

    /**
     * Add IP address as viewer for given framework item and return view count
     *
     * @param $id
     * @return int
     * @throws Exception
     */
    public static function viewForPost($id)
    {
        try {
            $count = 0;
            $ipAddress = request()->ip();

            $item = static::where('frameworkId', '=', $id)->where('address', '=', $ipAddress)->first();
            if (!$item) {
                $item = new self();
                $item->frameworkId = $id;
                $item->address = $ipAddress;
                $item->save();
            }

            $count = Cache::remember('view_for_framework_' . $id, 60 * 15, function() use ($id) {
                return static::where('frameworkId', '=', $id)->count();
            });

            return $count;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate a string representation for the view count
     *
     * @param $count
     * @return string
     * @throws Exception
     */
    public static function viewCountAsString($count)
    {
        try {
            if ($count >= self::COUNT_MILLION) {
                return strval(round($count / self::COUNT_MILLION, 1)) . 'M';
            } else if (($count < self::COUNT_MILLION) && ($count >= self::COUNT_HUNDREDTHOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else if (($count < self::COUNT_HUNDREDTHOUSAND) && ($count >= self::COUNT_TENTHOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else if (($count < self::COUNT_TENTHOUSAND) && ($count >= self::COUNT_THOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else {
                return strval($count);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
