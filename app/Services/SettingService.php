<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    public function getSettings() :Collection
    {
        return Setting::query()->visible()->orderBy('order')->get();
    }

    public function update($request) :bool
    {
    }
}

