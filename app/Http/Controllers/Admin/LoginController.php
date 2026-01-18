<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show_login_view()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            /** @var Admin $admin */
            $admin = Auth::guard('admin')->user();
            $timeout = now()->subMinutes(15); // 15 دقيقة بدل 30

            if ($admin->is_logged_in && $admin->last_activity > $timeout) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['login' => 'هذا الحساب قيد الاستخدام على جهاز آخر'])->withInput();
            }

            $admin->is_logged_in = true;
            $admin->session_id = session()->getId();
            $admin->last_activity = now();
            $admin->last_ip = $request->ip();
            $admin->save();

            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['login' => 'اسم المستخدم أو كلمة المرور غير صحيحة'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $admin->is_logged_in = false;
            $admin->session_id = null;
            $admin->last_activity = null;
            $admin->last_ip = null;
            $admin->save();
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.showlogin');
    }
}
