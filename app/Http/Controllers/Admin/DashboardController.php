<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	public function index()
	{
		$web_title = 'Dashboard';
		return view('admin.dashboard.index', compact('web_title'));
	}
}