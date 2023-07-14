<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(private readonly AccountService $accountService)
    {
        $this->middleware('auth:web');
    }

    public function show(Request $request) :View
    {
        $user = $this->accountService->me($request);

        return view('site.account', compact('user'));
    }

    public function update(AccountRequest $request) :RedirectResponse
    {
        $response = $this->accountService->update($request);
        return redirect()->route('account.show')
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
