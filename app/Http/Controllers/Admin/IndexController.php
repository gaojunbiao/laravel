<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index(){

    	return view('admin.index');
    }
    //欢迎页
    public function welcome(){

    	return view('admin.welcome');
    }
    //退出登录
    public function logout(){
    	session()->flush();
    	return redirect('admin/login');
    }
    
}
