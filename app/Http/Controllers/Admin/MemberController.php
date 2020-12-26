<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin\Member;
use DB;
use Hash;
use Validator;
use Storage;
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     * member首页
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // DB::connection()->enableQueryLog();
        // DB::getQueryLog();
        $start = $request->start;
        $end = $request->end;
        $name = $request->name;
        $where = function ($query) use ($request) {
            if ($request->has('name') && $request->name != '') {
                $search = "%" . $request->name . "%";
                $query->where('name', 'like', $search);
            }
            if ($request->has(['start','end']) && $request->start != ''&& $request->end != '') {
                $start = strtotime($request->start.'00:00:00');
                $end = strtotime($request->end.'11:59:59');
                $query->whereBetween('create_time', [strtotime($request->start.'00:00:00'),strtotime($request->end.'11:59:59')]);
            }
        };
        $list = Member::where($where)->paginate($this->row);
        $number = count($list);
        return view('admin.member.index',compact('list','start','end','name','number'));

    }

    /**
     * Show the form for creating a new resource.
     * 增加member
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.member.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $validator = Validator::make($data,[
            'name'=>'required|min:1|max:20',
            'password'=>'required|min:6|max:16|confirmed',
            'password_confirmation'=>'required|min:6|max:16',
            'email'=>'required|email',
            'phone'=>'regex:/^1[345789][0-9]{9}$/',
        ]);
        
        if ($validator->fails()) {
            return ['code'=>500,'info'=>'添加失败','data'=>[]];
        }else{ 
            $data['create_time'] = time();
            $data['password'] = Hash::make($data['password']);
            unset($data['password_confirmation']);
            unset($data['file']);
            //验证用户名唯一
            $checkname = Member::where('name',$data['name'])->first();
            if ($checkname) {
                return ['code'=>500,'info'=>'会员已存在','data'=>[]];
            }
            //验证邮箱唯一
            $checkemail = Member::where('email',$data['email'])->first();
            if ($checkemail) {
                return ['code'=>500,'info'=>'邮箱已存在','data'=>[]];
            }
            //验证手机号唯一
            $checkphone = Member::where('phone',$data['phone'])->first();
            if ($checkphone) {
                return ['code'=>500,'info'=>'手机号已存在','data'=>[]];
            }
            $member = Member::create($data);
            if ($member) {
                return ['code'=>200,'info'=>'添加成功','data'=>$member];
            }else{
                return ['code'=>500,'info'=>'添加失败','data'=>[]];
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::where('id',$id)->first();
        return view('admin.member.edit',compact('member'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = $request->except(['_token']);
        $data['update_time'] = time();
        $messages = [
            // 'upload.required' => '请上传图片',
        ];
        $validator = Validator::make($data,[
            'name'=>'required|min:1|max:20',
            'email'=>'required|email',
            'phone'=>'regex:/^1[345789][0-9]{9}$/',
            // 'upload'=>'required',

        ],$messages);

        $member =  Member::where('id',$data['id'])->first();

        if ($validator->fails()) {
            $vdmessages = $validator->messages()->toArray();
            if (count($vdmessages)>0) {
                foreach ($vdmessages as $k => $v) {
                    $info = $v[0];
                }
            }
            return ['code'=>500,'info'=>$info,'data'=>[]];
        }else{ 
            unset($data['file']);
            //验证用户名唯一
            $checkname = Member::where('name','!=',$member['name'])->where('name',$data['name'])->first();
            //var_dump($checkname);die();
            if ($checkname) {
                return ['code'=>500,'info'=>'会员已存在','data'=>[]];
            }
            //验证邮箱唯一
            $checkemail = Member::where('email','!=',$data['email'])->where('email',$data['email'])->first();
            if ($checkemail) {
                return ['code'=>500,'info'=>'邮箱已存在','data'=>[]];
            }
            //验证手机号唯一
            $checkphone = Member::where('phone','!=',$data['phone'])->where('phone',$data['phone'])->first();
            if ($checkphone) {
                return ['code'=>500,'info'=>'手机号已存在','data'=>[]];
            } 
            //验证图片是否改变，改变则删除原来的，然不变(此判定视情况而定)
            if ($request->has('upload') && $member['upload'] != $data['upload']) {
                $destroy = Storage::disk('public')->delete($member['upload']);
            }
            $member = Member::where('id',$data['id'])->update($data);

            if ($member) {
                return ['code'=>200,'info'=>'修改成功','data'=>$member];
            }else{
                return ['code'=>500,'info'=>'修改失败','data'=>[]]; 
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     * 删除member
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Member::destroy($id);
        if ($destroy) {
          return ['code'=>200,'info'=>'删除成功','data'=>$destroy];
        }else{
          return ['code'=>500,'info'=>'删除失败','data'=>[]];
        }
    }
    //批量删除member
    public function delAll(Request $request){
        // $ids = $request->all();
        $ids = $request->all()['ids'];
        $destroy = Member::destroy($ids);
        if ($destroy) {
          return ['code'=>200,'info'=>'删除成功','data'=>$destroy];
        }else{
          return ['code'=>500,'info'=>'删除失败','data'=>[]];
        }

    }
    //修改member状态
    public function status($id,$status){
        $save = Member::where('id',$id)->update(['status'=>$status]);
        if ($save) {
          return ['code'=>200,'info'=>'修改成功','data'=>$save];
        }else{
          return ['code'=>500,'info'=>'修改失败','data'=>[]];
        }

    }
    //上传头像
    public function upload(Request $request){
        if(!$request->hasFile('file')){
            //$request->session()->flash('error_msg','文件不存在');
            return ['code'=>500,'info'=>'文件不存在','data'=>[]];
        }
        $path = $request->file('file')->store('member/'.date('Ym'));
        if ($path) {
          return ['code'=>200,'info'=>'上传成功','data'=>['htmlpath'=>'/storage/'.$path,'sqlpath'=>$path]];
        }else{
          return ['code'=>500,'info'=>'上传失败','data'=>[]];
        }
    }
    //上传头像
    public function editpass(Request $request){
        if ($request->method()=='POST') {
            $data = $request->except(['_token']);
            $validator = Validator::make($data,[
                'oldpass'=>'required|min:6|max:16',
                'newpass'=>'required|min:6|max:16|confirmed',
                'newpass_confirmation'=>'required|min:6|max:16',
               
            ]);
            if ($validator->fails()) {
                return ['code'=>500,'info'=>'修改密码失败','data'=>[]];
            }
            //验证旧密码是否正确
            $password = Member::where('id',$data['id'])->first();
            if (!Hash::check($data['oldpass'],$password['password'])) {
                return ['code'=>500,'info'=>'旧密码错误','data'=>[]];
            }
            $editpass = Member::where('id',$data['id'])->update(['password'=>Hash::make($data['newpass'])]);
            if ($editpass) {
              return ['code'=>200,'info'=>'修改密码成功','data'=>[]];
            }else{
              return ['code'=>500,'info'=>'修改密码失败','data'=>[]];
            }
        }else{
            //11
            $member = Member::where('id',$request->route('id'))->first();
            return view('admin.member.editpass',compact('member'));
        }
        
    }

}
