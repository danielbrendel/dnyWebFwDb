<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ImageModel;

/**
 * Class FrameworkModel
 * 
 * Interface to framework item management
 */
class FrameworkModel extends Model
{
    use HasFactory;

    /**
     * Store framework item
     * 
     * @param $attr
     * @param $item
     * @param $userId
     * @param $isEdited
     * @return int
     * @throws \Exception
     */
    private static function storeFramework($attr, $item, $userId = null, $isEdited = false)
    {
        try {
            if (strpos($attr['github'], 'https://github.com') !== 0) {
                throw new \Exception(__('app.invalid_github_link'));
            }

            if ($userId !== null) {
                $item->userId = auth()->id();
            }

            if (!$isEdited) {
                $item->approved = false;
            }

            if ((!isset($attr['tags'])) || ($attr['tags'] === null)) {
                $attr['tags'] = '';
            }

            $item->slug = Str::slug($attr['name']);
            $item->name = $attr['name'];
            $item->langId = $attr['lang'];
            $item->creator = $attr['creator'];
            $item->summary = $attr['summary'];
            $item->description = $attr['description'];
            $item->tags = $attr['tags'] . ' ';
            $item->github = str_replace('https://github.com/', '', $attr['github']);
            $item->website = $attr['website'];
            $item->twitter = $attr['twitter'];

            $item->twitter = str_replace('https://twitter.com/', '', $item->twitter);
            $item->twitter = str_replace('@', '', $item->twitter);

            $image = request()->file('logo');
            if ($image !== null) {
                if ($image->getSize() > env('APP_MAXUPLOADSIZE')) {
                    throw new \Exception(__('app.post_upload_size_exceeded'));
                }

                $fname = uniqid('', true) . md5(random_bytes(55));
                $fext = $image->getClientOriginalExtension();

                $image->move(public_path() . '/gfx/logos/', $fname . '.' . $fext);

                $baseFile = public_path() . '/gfx/logos/' . $fname;
                $fullFile = $baseFile . '.' . $fext;

                if (!ImageModel::isValidImage(public_path() . '/gfx/logos/' . $fname . '.' . $fext)) {
                    throw new \Exception('Invalid image uploaded');
                }

                if (!ImageModel::createThumbFile($fullFile, ImageModel::getImageType($fext, $baseFile), $baseFile, $fext)) {
                    throw new \Exception('createThumbFile failed', 500);
                }

                unlink(public_path() . '/gfx/logos/' . $fname . '.' . $fext);

                $item->logo = $fname . '_thumb.' . $fext;
            }

            $item->save();

            return $item->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add framework item
     * 
     * @param $attr
     * @return int
     * @throws \Exception
     */
    public static function addFramework($attr)
    {
        try {
            $item = new self();
            return static::storeFramework($attr, $item, auth()->id());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit framework item
     * 
     * @param $id
     * @param $attr
     * @return int
     * @throws \Exception
     */
    public static function editFramework($id, $attr)
    {
        try {
            $item = static::where('id', '=', $id)->first();
            if (($item->userId !== auth()->id()) || (!User::isAdmin(auth()->id()))) {
                throw new \Exception('Insufficient permissions');
            }

            return static::storeFramework($attr, $item, null, true);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Query a list of frameworks
     * 
     * @param $lang
     * @param $paginate
     * @param $text_search
     * @param $tag
     * @return mixed
     * @throws \Exception
     */
    public static function queryFrameworks($lang = '_all_', $paginate = null, $text_search = null, $tag = null)
    {
        try {
            if ($lang !== '_all_') {
                $query = static::where('langId', '=', $lang);
            } else {
                $query = static::where('langId', '>', 0);
            }

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            if ($text_search !== null) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($text_search)) . '%'])
                    ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . trim(strtolower($text_search)) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . trim(strtolower($text_search)) . '%']);
            }

            if ($tag !== null) {
                $query->whereRaw('LOWER(tags) LIKE ?', ['%' . $tag . ' ' . '%']);
            }

            $query->where('approved', '=', true)->where('locked', '=', false);

            return $query->orderBy('id', 'desc')->limit(env('APP_MAXQUERYCOUNT'))->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Query framework items by a specific user
     * 
     * @param $userId
     * @param $paginate
     * @return mixed
     * @throws \Exception
     */
    public static function queryUserFrameworks($userId, $paginate = null)
    {
        try {
            $query = static::where('userId', '=', $userId);

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            return $query->orderBy('id', 'desc')->limit(env('APP_MAXQUERYCOUNT'))->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Query random framework items
     * 
     * @param $exclude
     * @param $lang
     * @param $limit
     * @return mixed
     * @throws \Exception
     */
    public static function queryRandom($exclude, $lang, $limit)
    {
        try {
            if ($lang !== '_all_') {
                $query = static::where('langId', '=', $lang);
            } else {
                $query = static::where('langId', '>', 0);
            }

            return $query->where('id', '<>', $exclude)->where('approved', '=', true)->where('locked', '=', false)->limit($limit)->inRandomOrder()->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get framework item by slug
     * 
     * @param $slug
     * @param $check_flags
     * @return mixed
     * @throws \Exception
     */
    public static function getBySlug($slug, $check_flags = true)
    {
        try {
            $query = static::where('slug', '=', $slug);

            if ($check_flags) {
                $query->where('locked', '=', false)->where('approved', '=', true);
            }

            return $query->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
