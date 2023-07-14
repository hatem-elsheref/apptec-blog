<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly AdminService $adminService){}

    public function index() :View
    {
        $data = $this->adminService->getStatistics();

        return view('admin.home', $data);
    }
}
