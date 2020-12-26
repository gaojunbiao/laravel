<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	//分页每页个数
	public $row  = 10;
	function getTree($arr,$pid=0,$level=0)
    {
        $list =array();
        foreach ($arr as $k=>$v){
            if ($v['pid'] == $pid){
                $v['level']=$level;
                $child=$this->GetTree($arr,$v['id'],$level+1);
                if(count($child)){
                    $v['leaf'] = false; //是否子节点
                    $v['child'] = $child;
                }else{
                    $v['leaf'] = true;
                }
                $list[] = $v;
            }
        }
        return $list;
    }

    // ──a
    // ───aa
    // ──b
    // ───bb
    public function GetTreeList($arr, $pid=0, $level=0)
    {
        global $tree;
        foreach ($arr as $key => $val) {
            if ($val['pid'] == $pid) {
                $flg = str_repeat('─', $level); // →
                if ($val['pid']!=0) {
                	$val['cate_name'] = '|'.$flg . $val['cate_name'];
                }else{
                	$val['cate_name'] =  $flg . $val['cate_name'];
                }
                $val['leaf'] = true;
                $tree[] = $val;
                $this->GetTreeList($arr, $val['id'], $level + 1);

            }

        }
         return $tree;
    }
}
