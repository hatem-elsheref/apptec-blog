<?php

namespace App\Services;

use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SettingService
{
    public function getSettings() :Collection
    {
        return Setting::query()->visible()->orderBy('order')->get();
    }

    public function update($request) :array
    {
        try {
            DB::beginTransaction();
            foreach ($request->validated() as $key => $value){
                $item = Setting::query()->where('id', str_replace('setting_', '', $key))->first();

                if ($item->is_file && $value instanceof UploadedFile){

                    if (File::exists(storage_path('app' . DIRECTORY_SEPARATOR . $item->value))){
                        File::delete(storage_path('app' . DIRECTORY_SEPARATOR . $item->value));
                    }

                    $value = $value->storeAs('uploads' . DIRECTORY_SEPARATOR . 'setting', Str::uuid()->toString().'.'.$value->getClientOriginalExtension());
                }

                $item->update([
                    'value' => $value
                ]);
            }
            DB::commit();
            Cache::delete('settings');
            return [
                'type'    => 'success',
                'message' => 'Settings Updated Successfully'
            ];

        }catch (Exception $exception){
            Log::error($exception->getMessage());
            DB::rollBack();

            return [
                'type'    => 'danger',
                'message' => 'Failed To Update Settings'
            ];
        }
    }
}

