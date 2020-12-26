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
  
  <body class="layui-anim layui-anim-up">

    <div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">会员管理</a>
        <a>
          <cite>会员列表</cite></a>
      </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="x-body">
      <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" method="get" action="{{url('admin/member')}}">
          <input class="layui-input" placeholder="开始日" name="start" id="start" value="{{$start}}">
          <input class="layui-input" placeholder="截止日" name="end" id="end" value="{{$end}}">
          <input type="text" name="name"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="{{$name}}">
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加用户','{{url('admin/member/create')}}',600,400)"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：{{$number}} 条</span>
      </xblock>
      <table class="layui-table">
        <thead>
          <tr>
            <th>
              <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>用户名</th>
            <th>性别</th>
            <th>手机</th>
            <th>头像</th>
            <th>邮箱</th>
            <!-- th>地址</th> -->
            <th>加入时间</th>
            <th>状态</th>
            <th>操作</th></tr>
        </thead>
        <tbody>
           @if(count($list)>0)
           @foreach($list as $k=>$v)
          <tr>
            <td>
              <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='{{$v->id}}'><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td>{{$v->id}}</td>
            <td>{{$v->name}}</td>
            @if($v->sex==1)
            <td>男</td>
            @else
            <td>女</td>
            @endif
            <td>{{$v->phone}}</td>
            <td style="text-align:center">

              <img src="@if($v->upload==''){{defaultimg($v->sex)}}@else /storage/{{$v->upload}} @endif" width="80px" height="80px;">

            </td>
            <td>{{$v->email}}</td>
            <td>{{date('Y-m-d H:i:s',$v->create_time)}}</td>
            <td class="td-status">
            @if($v->status==1)
            <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span></td>
            @else
            <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span></td>
            @endif
            <td class="td-manage">
              @if($v->status==1)
              <a onclick="member_oporstop(this,'{{$v->id}}')" href="javascript:;"  title="已启用">
                <i class="layui-icon">&#xe601;</i>
              </a>
              @else
              <a onclick="member_oporstop(this,'{{$v->id}}')" href="javascript:;"  title="已停用">
                <i class="layui-icon">&#xe62f;</i>
              </a>
              @endif
              <a title="编辑"  onclick="x_admin_show('编辑','{{url('admin/member/'.$v->id.'/edit')}}',600,400)" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
              </a>
              <a onclick="x_admin_show('修改密码','{{url('admin/member/editpass',['id'=>$v->id])}}',600,400)" title="修改密码" href="javascript:;">
                <i class="layui-icon">&#xe631;</i>
              </a>
              <a title="删除" onclick="member_del(this,'{{$v->id}}')" href="javascript:;">
                <i class="layui-icon">&#xe640;</i>
              </a>
            </td>
          </tr>
          @endforeach
          @endif
        </tbody>
      </table>
      <div class="page">
        <!-- <div>
          <a class="prev" href="">&lt;&lt;</a>
          <a class="num" href="">1</a>
          <span class="current">2</span>
          <a class="num" href="">3</a>
          <a class="num" href="">489</a>
          <a class="next" href="">&gt;&gt;</a>
        </div> -->
        {{$list->render()}}
      </div>

    </div>
    <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      layui.use('laydate', function(){
        var laydate = layui.laydate;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-启停用*/
      function member_oporstop(obj,id){
          console.log($(obj).attr('title'));
          var msg = $(obj).attr('title')=='已启用'?'停用':'启用';
          layer.confirm('确认要'+msg+'吗？',function(index){
              
              if($(obj).attr('title')=='已启用'){
                //发异步把用户状态进行更改
                $.get('/admin/member/'+id+'/2/'+'status',function(data){
                    if (data.code == 200) {
                      $(obj).attr('title','停用')
                      $(obj).find('i').html('&#xe62f;');

                      $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                      layer.msg('已停用!',{icon: 6,time:1000});
                    }else{
                      layer.msg('停用失败!',{icon: 5,time:1000});
                    }
                },'json');
                

              }else{
                $.get('/admin/member/'+id+'/1/'+'status',function(data,status){
                    if (data.code == 200) {
                      $(obj).attr('title','启用')
                      $(obj).find('i').html('&#xe601;');

                      $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                      layer.msg('已启用!',{icon: 6,time:1000});
                    }else{
                      layer.msg('启用失败!',{icon: 5,time:1000});
                    }
                },'json');
                
              }
              
          });
      }
      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.post('/admin/member/'+id,{'_token':"{{csrf_token()}}",'_method':'delete'},function(data,status){
                  if (data.code == 200) {
                    $(obj).parents("tr").remove();
                    layer.msg('删除成功!',{icon:1,time:1000});
                  }else{
                    layer.msg('删除失败!',{icon:2,time:1000});
                  }
              },'json');
              
          });
      }
      /*用户-多选删除*/
      function delAll (argument) {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            $.post('/admin/member/delAll',{ids:data},function(data,status){
                  if (data.code == 200) {
                    layer.msg('删除成功', {icon: 1});
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                  }else{
                    layer.msg('删除失败!',{icon:2,time:1000});
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