<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8">
    <title>欢迎页面-X-admin2.0</title>
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    @include('admin.public.styles')
    @include('admin.public.scripts')
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>
    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">分类管理</a>
        <a>
          <cite>多级分类</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so layui-form-pane">
          <input class="layui-input" placeholder="分类名" name="cate_name">
          <input type="hidden" name="pid" value="0">
          <button class="layui-btn"  lay-submit="" lay-filter="add"><i class="layui-icon"></i>增加</button>
        </form>
      </div>
      <blockquote class="layui-elem-quote">每个tr 上有两个属性 cate-id='1' 当前分类id fid='0' 父级id ,顶级分类为 0，有子分类的前面加收缩图标<i class="layui-icon x-show" status='true'>&#xe623;</i></blockquote>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="x-right" style="line-height:40px">共有数据：{{$number}} 条</span>
      </xblock>
      <table class="layui-table layui-form">
        <thead>
          <tr>
            <th width="20">
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th width="70">ID</th>
            <th>栏目名</th>
            <th width="50">排序</th>
            <th width="50">状态</th>
            <th width="220">操作</th>
        </thead>
        <tbody class="x-cate">
          @foreach($list as $k => $v)
          <tr cate-id="{{$v['id']}}" fid="{{$v['pid']}}" >
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="{{$v['id']}}">
                <i class="layui-icon">&#xe605;</i>
              </div>
            </td>
            <td>{{$v['id']}}</td>
            <td>
              <i class="layui-icon x-show" status="true">&#xe623;</i>
              {{$v['cate_name']}}
            </td>
            <td><input type="text" class="layui-input x-sort" name="order" value="{{$v['sort']}}"></td>
            <td>
              <input type="checkbox" name="switch"  lay-filter="status" lay-text="开启|停用"
               @if($v['status']==1) checked @endif lay-skin="switch" value="{{$v['id']}}">
            </td>
            <td class="td-manage">
              <button class="layui-btn layui-btn layui-btn-xs"  onclick="x_admin_show('编辑','admin-edit.html')" >
                <i class="layui-icon">&#xe642;</i>编辑
              </button>
              <button class="layui-btn layui-btn-warm layui-btn-xs"  onclick="x_admin_show('编辑','admin-edit.html')" >
                <i class="layui-icon">&#xe642;</i>添加子栏目
              </button>
              <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this,'{{$v['id']}}')" href="javascript:;" >
                <i class="layui-icon">&#xe640;</i>删除
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      layui.use(['form'], function(){
        form = layui.form;
        layer  = layui.layer;
        //监听提交
        form.on('submit(add)', function(data){
          //发异步，把数据提交给php
            $.post('/admin/cate',data.field,function(res){
              if (res.code == 200) {
                layer.open({
                  content: res.info,
                  icon:6,
                  yes: function(index, layero){
                 
                    location.reload();
                   
                  },
                  cancel: function(index, layero){ 
                    layer.close(index)
                    return false; 
                  },    
                  success: function(layero, index){
                    
                  }
                });
              }else{
                layer.open({
                  content: res.info,
                  icon:5,
                  yes: function(index, layero){
                    //do something
                    layer.close(index)
                  },
                  cancel: function(index, layero){ 
                    layer.close(index)
                    return false; 
                  },    
                  success: function(layero, index){
                    
                  }
                });
              }
            },'json');
            return false;
          });
          //监听复选框
          form.on('switch(status)', function(data){
            var status = data.elem.checked == true?1:2;
            var id = data.value;
            $.get('/admin/cate/'+id+'/'+status+'/status',function(res){
              if (res.code == 200) {
                layer.msg(res.info,{icon:6,time:2000});
              }else{
                layer.msg(res.info,{icon:5,time:2000});
              }
            });
          });
        });
      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.post('/admin/cate/'+id,{'_token':"{{csrf_token()}}",'_method':'delete'},function(data,status){
                  if (data.code == 200) {
                    $(obj).parents("tr").remove();
                    layer.msg(data.info,{icon:1,time:1000});
                  }else{
                    layer.msg(data.info,{icon:2,time:1000});
                  }
              },'json');
          });
      }
      /*用户-多选删除*/
      function delAll (argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？',function(index){
            //捉到所有被选中的，发异步进行删除
            $.post('/admin/cate/delAll',{ids:data},function(data,status){
                  if (data.code == 200) {
                    layer.msg(data.info,{icon:1,time:1000});
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                  }else{
                    layer.msg(data.info,{icon:2,time:1000});
                  }
              },'json');
        });
      }
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>