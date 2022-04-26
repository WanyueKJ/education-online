<?php /*a:1:{s:71:"/www/wwwroot/edudemo.sdwanyue.com/themes/admin_htcyltd/admin/login.html";i:1645779015;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8"/>
  <title>登入 - <?php echo lang('ADMIN_CENTER'); ?></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <!-- HTML5 shim for IE8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/style/login.css" media="all">
  <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/themes/admin_htcyltd/public/layuiadmin/layui/css/layui.css" media="all">
  <!--[if lt IE 9]>
  <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script>
    if (window.parent !== window.self) {
      document.write              = '';
      window.parent.location.href = window.self.location.href;
      setTimeout(function () {
        document.body.innerHTML = '';
      }, 0);
    }
  </script>
  <script src="/themes/admin_htcyltd/public/assets/js/jquery-1.10.2.min.js"></script>
  <script src="/static/js/jquery.validate/jquery.validate.js"></script>
  <script src="/static/js/ajaxForm.js"></script>

  <script src="/themes/admin_htcyltd/public/layuiadmin/layui/layui.js"></script>
  <script src="/static/js/admin.js"></script>
</head>
<body>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
  <div class="layadmin-user-login-main">
    <div class="layadmin-user-login-box layadmin-user-login-header">
      <h2><?php echo $configpub['site_name']; ?></h2>
      <p>后台管理系统</p>
    </div>
    <form class="layui-form js-ajax-form" method="post" action="<?php echo url('admin/public/doLogin'); ?>">
      <div class="layadmin-user-login-box layadmin-user-login-body">
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
          <input type="text" id="input_username" required class="layui-input" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="<?php echo lang('USERNAME_OR_EMAIL'); ?>" title="<?php echo lang('USERNAME_OR_EMAIL'); ?>" value="<?php echo cookie('admin_username'); ?>">
        </div>
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="password" required id="LAY-user-login-password" lay-verify="required" placeholder="<?php echo lang('PASSWORD'); ?>" title="<?php echo lang('PASSWORD'); ?>" class="layui-input">
        </div>
        <div class="layui-form-item">
          <div class="layui-row">
            <div class="layui-col-xs7">
              <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
              <input type="text" name="captcha" maxlength="4" lay-verify="required" placeholder="图形验证码" class="layui-input">
            </div>
            <div class="layui-col-xs5">
              <div style="margin-left: 10px;">
                <?php $__CAPTCHA_SRC=url('/new_captcha').'?id=LAY-user-get-vercode&height=37&width=129&font_size=18'; ?>
<img src="<?php echo $__CAPTCHA_SRC; ?>" onclick="this.src='<?php echo $__CAPTCHA_SRC; ?>&time='+Math.random();" title="换一张" class="captcha captcha-img verify_img" style="cursor: pointer;"/>
<input type="hidden" name="_captcha_id" value="LAY-user-get-vercode">
              </div>
            </div>
          </div>
        </div>
        <div class="layui-form-item">
          <input type="hidden" name="redirect" value="">
          <button class="layui-btn layui-btn-fluid js-ajax-submit" type="submit" data-loadingmsg="<?php echo lang('LOADING'); ?>"><?php echo lang('LOGIN'); ?></button>
        </div>
    </form>
  </div>
</div>
<div class="layui-trans layadmin-user-login-footer">
<!--  <p>© 2020 <a href="https://www.thinkcmf.com" target="_blank">thinkcmf.com</a></p>-->
<!--  <p>-->
<!--    <span><a href="" target="_blank">获取授权</a></span>-->
<!--    <span><a href="" target="_blank">在线演示</a></span>-->
<!--    <span><a href="" target="_blank">前往官网</a></span>-->
<!--  </p>-->
</div>
</div>
<script type="text/javascript">
  //全局变量
  var GV = {
    ROOT: "/",
    WEB_ROOT: "/",
    JS_ROOT: "static/js/",
    APP: '' /*当前应用名*/
  };
</script>
<script>
  (function() {
    document.getElementById('input_username').focus();
  })();
</script>
</body>
</body>
</html>