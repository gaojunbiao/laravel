<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin\Cate;
use DB;
use Hash;
use Validator;
class CateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // $list = Cate::groupBy('pid')->orderBy('sort','asc')->get()->toArray();
        $cate = new Cate();
        $list = $this->GetTreeList($cate->orderBy('sort','asc')->get()->toArray());
        // $list =  $cate->tree();
        $number = count($list);
         //pd($list);
        return view('admin.cate.index',compact('list','number'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
             'cate_name.required' => '分类名称为空',
             'cate_name.min' => '分类名称最小长度为1',
             'cate_name.max' => '分类名称最大长度为10',
        ];
        $data = $request->except(['_token']);
        $validator = Validator::make($data,[
            'cate_name'=>'required|min:1|max:10',
        ],$messages);
        
        if ($validator->fails()) {
            $vdmessages = $validator->messages()->toArray();
            if (count($vdmessages)>0) {
                foreach ($vdmessages as $k => $v) {
                    $info = $v[0];
                }
            }
            return ['code'=>500,'info'=>$info,'data'=>[]];
        }else{ 
            $data['create_time'] = time();
            //验证分类名唯一
            $checkname = Cate::where('cate_name',$data['cate_name'])->first();
            if ($checkname) {
                return ['code'=>500,'info'=>'分类已存在','data'=>[]];
            }
            
            $cate = Cate::create($data);
            if ($cate) {
                return ['code'=>200,'info'=>'添加成功','data'=>$cate];
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
        $cate = Cate::where('id',$id)->first();
        return view('admin.cate.edit',compact('cate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $data = $request->except(['_token']);
        $validator = Validator::make($data,[
            'cate_name'=>'required|min:1|max:10',
            'sort'=>'required|numeric',
        ]);
        $cate =  Cate::where('id',$data['id'])->first();
        if ($validator->fails()) {
            $info = '';
            $vdmessages = $validator->messages()->toArray();
            if (count($vdmessages)>0) {
                foreach ($vdmessages as $k => $v) {
                    $info = $v[0];
                }
            }
            return ['code'=>500,'info'=>$info,'data'=>[]];
        }else{ 
            $data['update_time'] = time();
            //验证分类名唯一
            $checkname = Cate::where('cate_name','!=',$cate['cate_name'])->where('cate_name',$data['cate_name'])->first();
            if ($checkname) {
                return ['code'=>500,'info'=>'分类已存在','data'=>[]];
            }
            $cate = Cate::where('id',$id)->update($data);
            if ($cate) {
                return ['code'=>200,'info'=>'修改成功','data'=>$cate];
            }else{
                return ['code'=>500,'info'=>'修改失败','data'=>[]];
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     * 删除Cate
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //验证是否有子类
        $checkchild = Cate::where('pid',$id)->first();
        if ($checkchild) {
           return ['code'=>500,'info'=>'请先删除子类','data'=>$checkchild];
        }
        $destroy = Cate::destroy($id);
        if ($destroy) {
          return ['code'=>200,'info'=>'删除成功','data'=>$destroy];
        }else{
          return ['code'=>500,'info'=>'删除失败','data'=>[]];
        }
    }
    //批量删除Cate
    public function delAll(Request $request){
        // $ids = $request->all();
        $flag = true;
        $ids = $request->all()['ids'];
        //验证是否有子类
        foreach ($ids as $k => $v) {
            $checkchild = Cate::where('pid',$v)->first();
            if ($checkchild) {
                return ['code'=>500,'info'=>'请先删除子类','data'=>$checkchild];
            }
        }
        $destroy = Cate::destroy($ids);
        if ($destroy) {
          return ['code'=>200,'info'=>'删除成功','data'=>$destroy];
        }else{
          return ['code'=>500,'info'=>'删除失败','data'=>[]];
        }

    }
   //修改Cate状态
    public function status($id,$status){
        $save = Cate::where('id',$id)->update(['status'=>$status]);
        $info = $status == 1 ? '开启成功' : '停用成功';
        if ($save) {
          return ['code'=>200,'info'=>$info,'data'=>$save];
        }else{
          return ['code'=>500,'info'=>'500','data'=>[]];
        }

    }
    //分类增加子栏目
    public function createson(Request $request){
        if ($request->method()=='POST') {

            $data = $request->except(['_token']);
            $validator = Validator::make($data,[
                'cate_name'=>'required|min:1|max:10',
                'sort'=>'required|numeric',
            ]);

            if ($validator->fails()) {
                $vdmessages = $validator->messages()->toArray();
                if (count($vdmessages)>0) {
                    foreach ($vdmessages as $k => $v) {
                        $info = $v[0];
                    }
                }
                return ['code'=>500,'info'=>$info,'data'=>[]];
            }else{ 
                $data['create_time'] = time();
                //验证分类名唯一
                $checkname = Cate::where('cate_name',$data['cate_name'])->first();
                if ($checkname) {
                    return ['code'=>500,'info'=>'分类已存在','data'=>[]];
                }
                $data['level'] = $data['level']+1;
                $cate = Cate::create($data);
                if ($cate) {
                    return ['code'=>200,'info'=>'添加成功','data'=>$cate];
                }else{
                    return ['code'=>500,'info'=>'添加失败','data'=>[]];
                }
            }
        }else{
           $id = $request->route('id');
           $level = $request->route('level');
          return view('admin.cate.createson',['id'=>$id,'level'=>$level]);  
        }
    }
    //分类排序
    public function sort($id,$sort){
      $sort = Cate::where('id',$id)->update(['sort'=>$sort]);
      if ($sort) {
        return ['code'=>200,'info'=>'排序成功','data'=>$sort];
      }else{
        return ['code'=>500,'info'=>'排序成功','data'=>[]];
      }
    }
}
