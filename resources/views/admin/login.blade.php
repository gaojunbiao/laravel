<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.0</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/X-admin/css/font.css">
	  <link rel="stylesheet" href="/X-admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/X-admin/lib/layui/layui.all.js" charset="utf-8"></script>
   
    <script type="text/javascript" src="/X-admin/js/xadmin.js"></script>

</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">x-admin2.0-管理登录</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form" >
            {{csrf_field()}}
            <input name="name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="captcha"  style="width:150px;float:left;" lay-verify="required" placeholder="验证码"  type="txt" class="layui-input">
            <img style="float:right;" src="{{captcha_src()}}" id="captcha" onclick="ChangeCaptcha()"/>
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>
        @if(session('errors'))
           <script>
              layer.msg('{{session('errors')}}', {time: 2000});
           </script>
        @endif 
    <script>
        function ChangeCaptcha(){
          $("#captcha").attr('src',$("#captcha")[0].src+Math.random());
        }
        $(function  () {
        
            layui.use('form', function(){
              var form = layui.form;
              var layer = layui.layer;
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              //监听提交
              form.on('submit(login)', function(data){
                $.post("/admin/login",data.field,function(res){
                  console.log(res.code);
                    if(res.code == 200){
                      layer.msg(res.info, {time: 2000});
                      var url = "/admin/index"; //
                      setTimeout(window.location.href=url,2000);
                    }else{
                      layer.msg(res.info, {time: 2000});
                      ChangeCaptcha();
                    }
                  },'json');
                return false;
              });
            });
        })

        
    </script>
    <!-- 底部结束 -->
    <script>
    //百度统计可去掉
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
</body>
</html>