<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function company()
    {
        return view('admin.settings/company');
    }

    public function currencies()
    {
        return view('admin.settings/currencies');
    }

    public function language()
    {
        return view('admin.settings/language');
    }

    public function notification()
    {
        return view('admin.settings/notification');
    }

    public function notificationAlert()
    {
        return view('admin.settings/notificationAlert');
    }

    public function paymentGateway()
    {
        return view('admin.settings/paymentGateway');
    }

    public function theme()
    {
        return view('admin.settings/theme');
    }

}
