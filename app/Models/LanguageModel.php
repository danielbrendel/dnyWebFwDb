<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LanguageModel
 * 
 * Interface to framework languages
 */
class LanguageModel extends Model
{
    use HasFactory;

    /**
     * Get full list of languages
     * 
     * @return mixed
     * @throws \Exception
     */
    public static function getLanguages()
    {
        try {
            return static::orderBy('language', 'asc')->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add new language
     * 
     * @param $name
     * @return void
     * @throws \Exception
     */
    public static function addLanguage($name)
    {
        try {
            $item = new self();
            $item->language = $name;
            $item->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit existing language
     * 
     * @param $id
     * @param $new_name
     * @return void
     * @throws \Exception
     */
    public static function editLanguage($id, $new_name)
    {
        try {
            $item = static::where('id', '=', $id)->first();
            if (!$item) {
                throw new \Exception('Language not found: ' . $id);
            }

            $item->language = $new_name;
            $item->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete existing language
     * 
     * @param $id
     * @return void
     * @throws \Exception
     */
    public static function deleteLanguage($id)
    {
        try {
            $item = static::where('id', '=', $id)->first();
            if (!$item) {
                throw new \Exception('Language not found: ' . $id);
            }

            $item->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
