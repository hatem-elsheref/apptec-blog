<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService){}

    public function index() :View
    {
        $users = $this->userService->listingAllUsers();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user) :View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(AccountRequest $request, User $user) :RedirectResponse
    {
        $response = $this->userService->update($request, $user);

        return redirect()->route('users.edit', $user->id)
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }

    public function destroy(User $user) :RedirectResponse
    {
        $response = $this->userService->delete($user);

        return redirect()->route('users.index')
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
