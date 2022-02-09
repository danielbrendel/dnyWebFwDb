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
 * Class AppModel
 * 
 * General app specific management
 */
class AppModel extends Model
{
    use HasFactory;

    const COUNT_MILLION = 1000000;
    const COUNT_HUNDREDTHOUSAND = 100000;
    const COUNT_TENTHOUSAND = 10000;
    const COUNT_THOUSAND = 1000;

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
     * Get cookie consent content
     * 
     * @return string
     * @throws \Exception
     */
    public static function getCookieConsent()
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
     * Get head code content
     * 
     * @return string
     * @throws \Exception
     */
    public static function getHeadCode()
    {
        try {
            return static::getAppSettings()->head_code;
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

    /**
     * Generate a string representation for the given count
     *
     * @param $count
     * @return string
     * @throws \Exception
     */
    public static function countAsString($count)
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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Lock entity
     * @param $id
     * @param $type
     * @return void
     * @throws Exception
     */
    public static function lockEntity($id, $type)
    {
        try {
            if ($type === 'ENT_USER') {
                $item = User::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else if ($type === 'ENT_FRAMEWORK') {
                $item = FrameworkModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else if ($type === 'ENT_REVIEW') {
                $item = ReviewModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else {
                throw new \Exception('Invalid type: ' . $type, 500);
            }

            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete entity
     * @param $id
     * @param $type
     * @throws Exception
     */
    public static function deleteEntity($id, $type)
    {
        try {
            if ($type === 'ENT_USER') {
                $item = User::where('id', '=', $id)->first();
                if ($item) {
                    $item->delete();
                }
            } else if ($type === 'ENT_FRAMEWORK') {
                $item = FrameworkModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->delete();
                }
            } else if ($type === 'ENT_REVIEW') {
                $item = ReviewModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->delete();
                }
            } else {
                throw new \Exception('Invalid type: ' . $type, 500);
            }

            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Set entity safe
     *
     * @param $id
     * @param $type
     * @return void
     * @throws Exception
     */
    public static function setEntitySafe($id, $type)
    {
        try {
            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Initialize newsletter sending
     *
     * @param $subject
     * @param $content
     * @return void
     * @throws Exception
     */
    public static function initNewsletter($subject, $content)
    {
        try {
            $token = md5($subject . $content . random_bytes(55));

            static::saveSetting('newsletter_token', $token);
            static::saveSetting('newsletter_subject', $subject);
            static::saveSetting('newsletter_content', $content);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Process newsletter job
     *
     * @return array
     * @throws Exception
     */
    public static function newsletterJob()
    {
        try {
            $result = array();

            $settings = static::getAppSettings();
            if ($settings->newsletter_token !== null) {
                $users = User::where('locked', '=', false)->where('account_confirm', '=', '_confirmed')->where('newsletter', '=', true)->where('newsletter_token', '<>', $settings->newsletter_token)->limit(env('APP_NEWSLETTER_UCOUNT'))->get();
                foreach ($users as $user) {
                    $user->newsletter_token = $settings->newsletter_token;
                    $user->save();

                    MailerModel::sendMail($user->email, $settings->newsletter_subject, $settings->newsletter_content);

                    $result[] = array('username' => $user->username, 'email' => $user->email, 'sent_date' => date('Y-m-d H:i:s'));
                }
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
