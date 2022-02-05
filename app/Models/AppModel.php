<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppModel
 * 
 * General app specific management
 */
class AppModel extends Model
{
    use HasFactory;

    /**
     * Get application settings
     * 
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getAppSettings($id = 1)
    {
        try {
            return static::where('id', '=', $id)->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get registration info
     * 
     * @return string
     * @throws \Exception
     */
    public static function getRegInfo()
    {
        try {
            return static::getAppSettings()->reg_info;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get cookie consent text
     * 
     * @return string
     * @throws \Exception
     */
    public static function getCookieConsentText()
    {
        try {
            return static::getAppSettings()->cookie_consent;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get about content
     * 
     * @return string
     * @throws \Exception
     */
    public static function getAbout()
    {
        try {
            return static::getAppSettings()->about;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get imprint content
     * 
     * @return string
     * @throws \Exception
     */
    public static function getImprint()
    {
        try {
            return static::getAppSettings()->imprint;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get terms of service content
     * 
     * @return string
     * @throws \Exception
     */
    public static function getTermsOfService()
    {
        try {
            return static::getAppSettings()->tos;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Save specific setting
     * 
     * @param $ident
     * @param $value
     * @return void
     * @throws \Exception
     */
    public static function saveSetting($ident, $value)
    {
        try {
            $settings = static::getAppSettings();
            $settings->$ident = $value;
            $settings->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
