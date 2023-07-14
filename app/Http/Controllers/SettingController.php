<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private readonly SettingService $settingService)
    {
        $this->middleware(['auth:web', 'admin']);
    }

    public function show() :View
    {
        $settings = $this->settingService->getSettings();

        return view('admin.setting', compact('settings'));
    }

    public function update(SettingRequest $request) :RedirectResponse
    {
        $response = $this->settingService->update($request);

        return redirect()->route('setting.show')
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
