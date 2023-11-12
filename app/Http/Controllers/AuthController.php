<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 显示登录表单
    public function showLoginForm()
    {
        return view('login');
    }

    // 登录处理
    public function login(Request $request)
    {
        // 使用 Laravel 的内置验证方法验证输入
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        
        // 尝试认证用户
        if (Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            return redirect()->intended('/');  // 登录成功后重定向到首页或者先前尝试访问的页面
        } else {
            return back()->withErrors(['error' => '用户名或密码错误']);
        }
    }

    // 登出处理
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
