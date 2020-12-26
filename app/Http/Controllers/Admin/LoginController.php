<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Captcha;
use Input;
use App\Models\admin\Login;
use Validator;
use Hash;
class LoginController extends Controller
{
    //
    public function login(Request $request){

    	if ($request->method() == 'POST') {
    		$data = $request->except(['_token']);
    		$validator = Validator::make($data,[
    			'name'=>'required|min:1|max:20',
    			'password'=>'required|min:6|max:20',
                'captcha'=>'required|captcha',
    		]);
    		if ($validator->fails()) {
            	// return redirect('admin/login/login')
             //            ->withErrors($validator)
             //            ->withInput();
    			return ['code'=>500,'info'=>'登录失败','data'=>[]];
       		}else{
       			$user = Login::where('name',$data['name'])->first();
    			if (!$user) {
    				return ['code'=>500,'info'=>'登录失败','data'=>[]];
    			}
    			if (Hash::check($data['password'],$user->password)) {
    				session()->put('user',$user);
    				return ['code'=>200,'info'=>'登录成功','data'=>$user];
    			}else{
    				return ['code'=>500,'info'=>'登录失败','data'=>[]];
    			}
       		}
    	}else{
    		return view('admin.login');
    	}
    	
    }
}
