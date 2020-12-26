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
              <label for="cate_name" class="layui-form-label">
                  <span class="x-red">*</span>分类名称
              </label>
              <div class="layui-input-inline">
                  <input type="text" id="cate_name" name="cate_name" required="" lay-verify="cate_name"
                  autocomplete="off" value="{{$cate['cate_name']}}" class="layui-input">
              </div>
              
          </div>
          <div class="layui-form-item">
              <label for="username" class="layui-form-label">
                  <span class="x-red">*</span>排序
              </label>
              <div class="layui-input-inline">
                  <input type="number" min="1" id="sort" name="sort" required="" lay-verify="required"
                  autocomplete="off" value="{{$cate['sort']}}" class="layui-input">
              </div>
             
          </div>
          <div class="layui-form-item">
              <label for="L_repass" class="layui-form-label">
              </label>
              <button  class="layui-btn" lay-filter="edit" lay-submit="">
                <input type="hidden" id="id" name="id" value="{{$cate['id']}}">
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
            cate_name: function(value){
              if(value.length > 10){
                return '最多10个字符';
              }
              if(value.length == 0){
                return '请输入分类名称';
              }
            }
          });

          //监听提交
          form.on('submit(edit)', function(data){
            //发异步，把数据提交给php
            // console.log(data.field.id);
            $.ajax({ 
              url: '/admin/cate/'+data.field.id,
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