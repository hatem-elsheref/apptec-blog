<?php

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

if (!function_exists('admin_assets')){
    function admin_assets($path) :string{
        return asset(sprintf('assets/admin/%s', $path));
    }
}

if (!function_exists('site_assets')){
    function site_assets($path) :string{
        return asset(sprintf('assets/site/%s', $path));
    }
}

if (!function_exists('setting')){
    function setting($key = null, $default = null) :string|null|array{
        $settings = (array) Cache::forever('setting', function (){
            $settings = [];
            Setting::query()->get()->each(function ($item) use (&$settings){
                $settings["$item->key__$item->group"] = $item->value;
            });

            return $settings;
        });

        return Arr::get($settings, $key, $default);
    }
}