<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AccountService
{
    public function me($request) :Model
    {
        return $request->user();
    }

    public function update($request) :array
    {
        try {

            $user = $this->me($request);

            $validatedData = $request->only('name', 'email');

            if ($request->password && $request->old_password){
                $validatedData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('avatar') && $request->avatar instanceof UploadedFile){
                $path = $request->avatar
                    ->storeAs('uploads' . DIRECTORY_SEPARATOR . 'users', Str::uuid()->toString().'.'.$request->avatar->getClientOriginalExtension());

                $validatedData['avatar'] = $path;

                if (File::exists(storage_path('app' . DIRECTORY_SEPARATOR . $user->avatar))){
                    File::delete(storage_path('app' . DIRECTORY_SEPARATOR . $user->avatar));
                }

            }else{
                $validatedData['avatar'] = $user->avatar;
            }

            $user->update($validatedData);

            return [
                'type'    => 'success',
                'message' => 'Profile Updated Successfully'
            ];
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return [
                'type'    => 'danger',
                'message' => 'Failed To Update Profile'
            ];
        }
    }
}

