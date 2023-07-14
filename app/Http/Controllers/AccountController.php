<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(private readonly UserService $userService){}

    public function show(Request $request) :View
    {
        $user = $this->userService->me($request);

        return view('site.account', compact('user'));
    }

    public function update(AccountRequest $request) :RedirectResponse
    {
        $response = $this->userService->update($request, $request->user());

        return redirect()->route('account.show')
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
