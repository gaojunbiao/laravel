<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use DB;
use App\Models\Index;

class IndexController extends Controller
{
    //
    public function index(){
    	// echo(Input::get('id','0'));
    	// dd(Input::only(['id','name']));
    	// dd(Input::all());
    	// dd(Input::except(['id','name']));
    	// dd(Input::has('id'));

    	// $db = DB::table('user');
    	// $result = $db->get();
    	// $dates = ['date','day'];
    	// return view('home/user/index',['date'=>$date]);
        $result = Index::get();
        // dd($result);
    	return view('home/user/index',compact('result'));
    }
    public function getuser(){
    	DB::connection()->enableQueryLog();
    	$db = DB::table('user');
    	// $result = $db->where('id',1)->get();
    	// foreach ($result as $k => $v) {
    	// 	echo "name:{$v -> name},password:{$v -> password}<br>";
    	// }
    	$result = $db->select('id','id as ids')->where('id',1)->first();
    	dd($result->id);
    	dd(DB::getQueryLog());
    }
    public function adduser(Request $request){
    	// $db = DB::table('user');
    	// $result = $db->insertGetId([
    	// 	'name'=>'2',
    	// 	'password'=>'2'
    	// ]);
    	// dd($result);

        if ($request->method() == 'POST') {
           $this->validate($request,[
                'name'=>'required|min:2|max:20',
                'password'=>'required|min:6|max:20',
                'email'=>'required|email'
           ]);

        }
        $result = Index::create($request->all());
        return $result;
       
    }
    public function updateuser(){
    	// $db = DB::table('user');
    	// $result = $db->where('id',1)->update([
    	// 	'name'=>'2222222',
    	// 	'password'=>'2'
    	// ]);
    	$result = Index::where('id',12)->update([
            'name'=>'哈哈哈哈'
        ]);
        return $result;
    }
    public function deleteuser(){
    	$db = DB::table('user');
    	$result = $db->delete(1);
    	dd($result);
    }

}
