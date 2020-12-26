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
  <style>
    .layui-form-switch{
      margin-top: 7px;
    }
  </style>
  <body>
    <div class="x-body layui-anim layui-anim-up">
        <form class="layui-form">
          <input type="hidden" id="id" name="id" value="{{$member['id']}}"/> 
          <div class="layui-form-item">
              <label for="L_username" class="layui-form-label">
                  <span class="x-red">*</span>昵称
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_username" name="name" required="" lay-verify="nikename"
                  autocomplete="off" class="layui-input" value="{{$member['name']}}">
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_email" class="layui-form-label">
                  <span class="x-red">*</span>邮箱
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_email" name="email" required="" lay-verify="email"
                  autocomplete="off" class="layui-input" value="{{$member['email']}}">
              </div>
              
          </div>
          <div class="layui-form-item">
              <label for="L_phone" class="layui-form-label">
                  <span class="x-red">*</span>手机号
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="L_phone" name="phone" required="" lay-verify="phone"
                  autocomplete="off" class="layui-input" value="{{$member['phone']}}">
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <span class="x-red">*</span>将会成为您唯一的登入名
              </div>
          </div>
          <div class="layui-form-item">
              <label for="L_sex" class="layui-form-label">
                  <span class="x-red">*</span>性别
              </label>
              <div class="layui-input-inline">
                  <input type="radio"  name="sex" value="1" title="男" @if($member['sex'] == 1) checked @endif>
                  <input type="radio"  name="sex" value="2" title="女" @if($member['sex'] == 2) checked @endif>
                 
              </div>
              <div class="layui-form-mid layui-word-aux">
                  <!-- <span class="x-red">*</span>将会成为您唯一的登入名 -->
              </div>
          </div>
          <div class="layui-form-item"> 
          　　<label class="layui-form-label">照片</label> 
          　　<div class="layui-upload"> 
          　　　　<!-- 上传按钮 -->
          　　　　<button type="button" class="layui-btn" id="uploadPic" style="float:left">
                    <i class="layui-icon">&#xe67c;</i>选择图片
                  </button>      
          　　　　<!-- 隐藏的input,一个隐藏的input（用于保存文件url） -->
          　　　　<input type="hidden" id="upload" name="upload" value="{{$member['upload']}}"/> 
          　　　　
          　　　　<!-- 预览区域 -->
          　　　　<div class="layui-upload-list" style="margin: 20px 26px;"> 
          　　　　　　<img class="layui-upload-img" width="113px" height="113px" id="uploadShow" src="/storage/{{$member['upload']}}"/> 
          　　　　</div>                
          　　</div> 
          </div>
          <div class="layui-form-item">
              <label for="L_status" class="layui-form-label">
                  <span class="x-red">*</span>状态
              </label>

              <div class="layui-input-inline">
                  <input type="checkbox" lay-filter="status"  lay-skin="switch" lay-text="开启|禁用" @if($member['status'] == 1) checked @endif>
                  <input type="hidden" name="status" value="{{$member['status']}}">
              </div>

          </div>
          
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="edit" lay-submit="">
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
        //上传图片
        layui.use('upload', function(){
          var upload = layui.upload;
          //执行实例
          var uploadInst = upload.render({
            elem: '#uploadPic' //绑定元素
            ,url: '/admin/member/upload' //上传接口
            ,accept:'images'//允许上传的文件类型images(图片),file(所有文件),video(视频),audio(音频)
            ,size:100//设置文件最大可允许上传的大小，单位 KB。不支持ie8/9
            ,headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            ,done: function(res){
              //上传完毕回调
              if (res.code==200) {
                $("#upload").val(res.data.sqlpath);
                $("#uploadShow").attr('src',res.data.htmlpath);
                layer.msg(res.info, {time: 2000});
              }else{
                layer.msg(res.info, {time: 2000});
              }
            }
            ,error: function(){
              //请求异常回调
            }
          });
        });
        //提交表单
        layui.use(['form','layer'], function(){
          $ = layui.jquery;
          var form = layui.form
          ,layer = layui.layer;
          //监听复选框
          form.on('switch(status)', function(data){
            var status = data.elem.checked == true?1:2;
            // console.log(data.elem); //得到checkbox原始DOM对象
            // console.log(data.elem.checked); //是否被选中，true或者false
            // console.log(data.value); //复选 框value值，也可以通过data.elem.value得到
            // console.log(data.othis); //得到美化后的DOM对象
            $("input[name='status']").val(status);

          }); 
          
          //自定义验证规则
          form.verify({
            nikename: function(value){
              if(value.length > 20){
                return '昵称最多20个字符啊';
              }
            }
          });
            
          //监听提交
          form.on('submit(edit)', function(data){
            //发异步，把数据提交给php
            // console.log(data.field.id);
            $.ajax({ 
              url: '/admin/member/'+data.field.id,
              data: data.field,
              type: 'PUT',
              success: function(res) {
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
                  parent.location.reload();
                   
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
              }
            });
            // $.put('/admin/member/'+data.field.id,data.field,function(res){},'json');
              
            
            
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