<html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <title>注册(Register)</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- CSS -->
        <link rel="stylesheet" href="/assets/css/reset.css">
        <link rel="stylesheet" href="/assets/css/supersized.css">
        <link rel="stylesheet" href="/assets/css/style.css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="assets/js/html5.js"></script>
        <![endif]-->
    </head>
    
    <body>
        @if(count($errors)>0)
        <div>
          <ul>
            @foreach($errors->all() as $errors)
            <li>{{$errors}}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <div class="page-container">
            <h1>注册(Register)</h1>
            <form action="/home/index/adduser" method="post">
                <input type="text" name="name" class="name" placeholder="请输入您的用户名！">
                <input type="password" name="password" class="password" placeholder="请输入您的用户密码！">
                <input type="test" name="email" class="email" placeholder="请输入您的邮箱！">
                <!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
                {{csrf_field()}}
                <input type="Captcha" class="Captcha" name="Captcha" placeholder="请输入验证码！">
                <img id="img" src="{{captcha_src()}}"/>
                <button type="submit" class="submit_button">注册</button>
                <div class="error"><span>+</span></div>
            </form>
        </div>
        <!-- Javascript -->
        <script src="/assets/js/jquery-1.8.2.min.js" ></script>
        <script src="/assets/js/supersized.3.2.7.min.js" ></script>
        <script src="/assets/js/supersized-init.js" ></script>
        <script src="/assets/js/scripts.js" ></script>
        <script type="text/javascript">
        $(function(){
            var url=$('img').attr('src');
            $('img').click(function(){
                $(this).attr('src',url+Math.random())
            })
        });
    </script>
    </body>
</html>

