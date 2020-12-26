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
    <div class="x-body">
        <form class="layui-form">
          <div class="layui-form-item">
              <label for="L_name" class="layui-form-label">
                  昵称
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_name" name="name" disabled="true" value="{{$member['name']}}" class="layui-input">
                  <input type="hidden" name="id" value="{{$member['id']}}">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_oldpass" class="layui-form-label">
                  <span class="x-red">*</span>旧密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_oldpass" name="oldpass" required="" lay-verify="pass"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_newpass" class="layui-form-label">
                  <span class="x-red">*</span>新密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_newpass" name="newpass" required="" lay-verify="pass"
                  autocomplete="off" class="layui-input">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  6到16个字符
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_newpass_confirmation" class="layui-form-label">
                  <span class="x-red">*</span>确认密码
              </label>
              <div class="layui-input-inline">
                  <input type="password" id="L_newpass_confirmation" name="newpass_confirmation" required="" lay-verify="repass"
                  autocomplete="off" class="layui-input">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="newpass_confirmation" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="save" lay-submit="">
                  保存
              </button>
          </div>
      </form>
    </div>
    <script>
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          layui.use(['form','layer'], function(){
            $ = layui.jquery;
            var form = layui.form
            ,layer = layui.layer;
            //自定义验证规则
          form.verify({
            pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_newpass').val()!=$('#L_newpass_confirmation').val()){
                    return '两次密码不一致';
                }
            }
          });
            //监听提交
            form.on('submit(save)', function(data){
              //发异步，把数据提交给php
            $.post('/admin/member/editpass',data.field,function(res){
            if (res.code == 200) {
              layer.open({
                content: res.info,
                icon:6,
                yes: function(index, layero){
                //do something
                //layer.close(index); //如果设定了yes回调，需进行手工关闭
                var parentindex = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(parentindex);
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
            
            
          });
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>

</html>